<?php

namespace CommerceGuys\Intl\Formatter;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\Exception\InvalidArgumentException;
use CommerceGuys\Intl\NumberFormat\NumberFormat;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepositoryInterface;

/**
 * Formats numbers using locale-specific patterns.
 */
class NumberFormatter implements NumberFormatterInterface
{
    /**
     * The number format repository.
     *
     * @var NumberFormatRepositoryInterface
     */
    protected $numberFormatRepository;

    /**
     * The formatting style.
     *
     * @var int
     */
    protected $style;

    /**
     * The default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * The loaded number formats.
     *
     * @var NumberFormat[]
     */
    protected $numberFormats = [];

    /**
     * The parsed number patterns, keyed by locale and style.
     *
     * @var ParsedPattern[]
     */
    protected $parsedPatterns = [];

    /**
     * Whether grouping is used.
     *
     * @var bool
     */
    protected $groupingUsed = true;

    /**
     * The minimum number of fraction digits to show.
     *
     * @var int
     */
    protected $minimumFractionDigits;

    /**
     * The maximum number of fraction digits to show.
     *
     * @var int
     */
    protected $maximumFractionDigits;

    /**
     * The currency display style.
     *
     * @var int
     */
    protected $currencyDisplay;

    /**
     * Localized digits.
     *
     * @var array
     */
    protected $digits = [
        NumberFormat::NUMBERING_SYSTEM_ARABIC => [
            0 => '٠', 1 => '١', 2 => '٢', 3 => '٣', 4 => '٤',
            5 => '٥', 6 => '٦', 7 => '٧', 8 => '٨', 9 => '٩',
        ],
        NumberFormat::NUMBERING_SYSTEM_ARABIC_EXTENDED => [
            0 => '۰', 1 => '۱', 2 => '۲', 3 => '۳', 4 => '۴',
            5 => '۵', 6 => '۶', 7 => '۷', 8 => '۸', 9 => '۹',
        ],
        NumberFormat::NUMBERING_SYSTEM_BENGALI => [
            0 => '০', 1 => '১', 2 => '২', 3 => '৩', 4 => '৪',
            5 => '৫', 6 => '৬', 7 => '৭', 8 => '৮', 9 => '৯',
        ],
        NumberFormat::NUMBERING_SYSTEM_DEVANAGARI => [
            0 => '०', 1 => '१', 2 => '२', 3 => '३', 4 => '४',
            5 => '५', 6 => '६', 7 => '७', 8 => '८', 9 => '९',
        ],
    ];

    /**
     * Creates a NumberFormatter instance.
     *
     * @param NumberFormatRepositoryInterface $numberFormatRepository The number format repository.
     * @param int                             $style                  The formatting style.
     * @param string                          $defaultLocale          The default locale. Defaults to 'en'.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct(NumberFormatRepositoryInterface $numberFormatRepository, $style = self::DECIMAL, $defaultLocale = 'en')
    {
        if (!extension_loaded('bcmath')) {
            throw new \RuntimeException('The bcmath extension is required by NumberFormatter.');
        }
        if (!in_array($style, [self::DECIMAL, self::PERCENT, self::CURRENCY, self::CURRENCY_ACCOUNTING])) {
            throw new InvalidArgumentException('Unknown format style provided to NumberFormatter::__construct().');
        }

        $this->numberFormatRepository = $numberFormatRepository;
        $this->style = $style;
        $this->defaultLocale = $defaultLocale;
        // Initialize the fraction digit settings for decimal and percent
        // styles only. The currency ones will default to the currency values.
        if (in_array($style, [self::DECIMAL, self::PERCENT])) {
            $this->minimumFractionDigits = 0;
            $this->maximumFractionDigits = 3;
        }
        $this->currencyDisplay = self::CURRENCY_DISPLAY_SYMBOL;
    }

    /**
     * {@inheritdoc}
     */
    public function format($number, $locale = null)
    {
        if (!is_numeric($number)) {
            $message = sprintf('The provided value "%s" must be a valid number or numeric string.', $number);
            throw new InvalidArgumentException($message);
        }

        $locale = $locale ?: $this->defaultLocale;
        $numberFormat = $this->getNumberFormat($locale);
        $parsedPattern = $this->getParsedPattern($numberFormat, $this->style);
        // Ensure that the value is positive and has the right number of digits.
        $negative = (bccomp('0', $number, 12) == 1);
        $signMultiplier = $negative ? '-1' : '1';
        $number = bcdiv($number, $signMultiplier, $this->maximumFractionDigits);
        // Split the number into major and minor digits.
        $numberParts = explode('.', $number);
        $majorDigits = $numberParts[0];
        // Account for maximumFractionDigits = 0, where the number won't
        // have a decimal point, and $numberParts[1] won't be set.
        $minorDigits = isset($numberParts[1]) ? $numberParts[1] : '';

        if ($this->groupingUsed && $parsedPattern->isGroupingUsed()) {
            // Reverse the major digits, since they are grouped from the right.
            $majorDigits = array_reverse(str_split($majorDigits));
            // Group the major digits.
            $groups = [];
            $groups[] = array_splice($majorDigits, 0, $parsedPattern->getPrimaryGroupSize());
            while (!empty($majorDigits)) {
                $groups[] = array_splice($majorDigits, 0, $parsedPattern->getSecondaryGroupSize());
            }
            // Reverse the groups and the digits inside of them.
            $groups = array_reverse($groups);
            foreach ($groups as &$group) {
                $group = implode(array_reverse($group));
            }
            // Reconstruct the major digits.
            $majorDigits = implode(',', $groups);
        }

        if ($this->minimumFractionDigits < $this->maximumFractionDigits) {
            // Strip any trailing zeroes.
            $minorDigits = rtrim($minorDigits, '0');
            if (strlen($minorDigits) < $this->minimumFractionDigits) {
                // Now there are too few digits, re-add trailing zeroes
                // until the desired length is reached.
                $neededZeroes = $this->minimumFractionDigits - strlen($minorDigits);
                $minorDigits .= str_repeat('0', $neededZeroes);
            }
        }

        // Assemble the final number and insert it into the pattern.
        $number = strlen($minorDigits) ? $majorDigits . '.' . $minorDigits : $majorDigits;
        $pattern = $negative ? $parsedPattern->getNegativePattern() : $parsedPattern->getPositivePattern();
        $number = preg_replace('/#(?:[\.,]#+)*0(?:[,\.][0#]+)*/', $number, $pattern);
        $number = $this->localizeNumber($number, $numberFormat);

        return $number;
    }

    /**
     * {@inheritdoc}
     */
    public function formatCurrency($number, Currency $currency, $locale = null)
    {
        // Use the currency defaults if the values weren't set by the caller.
        $resetMinimumFractionDigits = $resetMaximumFractionDigits = false;
        if (!isset($this->minimumFractionDigits)) {
            $this->minimumFractionDigits = $currency->getFractionDigits();
            $resetMinimumFractionDigits = true;
        }
        if (!isset($this->maximumFractionDigits)) {
            $this->maximumFractionDigits = $currency->getFractionDigits();
            $resetMaximumFractionDigits = true;
        }

        // Format the decimal part of the value first.
        $number = $this->format($number, $locale);

        // Reset the fraction digit settings, so that they don't affect
        // future formatting with different currencies.
        if ($resetMinimumFractionDigits) {
            $this->minimumFractionDigits = null;
        }
        if ($resetMaximumFractionDigits) {
            $this->maximumFractionDigits = null;
        }

        $symbol = '';
        if ($this->currencyDisplay == self::CURRENCY_DISPLAY_SYMBOL) {
            $symbol = $currency->getSymbol();
        } elseif ($this->currencyDisplay == self::CURRENCY_DISPLAY_CODE) {
            $symbol = $currency->getCurrencyCode();
        }

        return str_replace('¤', $symbol, $number);
    }

    /**
     * {@inheritdoc}
     */
    public function parse($number, $locale = null)
    {
        $locale = $locale ?: $this->defaultLocale;
        $numberFormat = $this->getNumberFormat($locale);
        $replacements = [
            $numberFormat->getGroupingSeparator() => '',
            // Convert the localized symbols back to their original form.
            $numberFormat->getDecimalSeparator() => '.',
            $numberFormat->getPlusSign() => '+',
            $numberFormat->getMinusSign() => '-',

            // Strip whitespace (spaces and non-breaking spaces).
            ' ' => '',
            chr(0xC2) . chr(0xA0) => '',
        ];
        $numberingSystem = $numberFormat->getNumberingSystem();
        if (isset($this->digits[$numberingSystem])) {
            // Convert the localized digits back to latin.
            $replacements += array_flip($this->digits[$numberingSystem]);
        }

        $number = strtr($number, $replacements);
        if (substr($number, 0, 1) == '(' && substr($number, -1, 1) == ')') {
            // This is an accounting formatted negative number.
            $number = '-' . str_replace(['(', ')'], '', $number);
        }

        return is_numeric($number) ? $number : false;
    }

    /**
     * {@inheritdoc}
     */
    public function parseCurrency($number, Currency $currency, $locale = null)
    {
        $replacements = [
            // Strip the currency code or symbol.
            $currency->getCurrencyCode() => '',
            $currency->getSymbol() => '',
        ];
        $number = strtr($number, $replacements);

        return $this->parse($number, $locale);
    }

    /**
     * Localizes the number according to the number format.
     *
     * Both the digits and the symbols are replaced
     * with their localized equivalents.
     *
     * @param string       $number       The number.
     * @param NumberFormat $numberFormat The number format.
     *
     * @return string The localized number.
     *
     * @see http://cldr.unicode.org/translation/number-symbols
     */
    protected function localizeNumber($number, NumberFormat $numberFormat)
    {
        // Localize digits.
        $numberingSystem = $numberFormat->getNumberingSystem();
        if (isset($this->digits[$numberingSystem])) {
            $number = strtr($number, $this->digits[$numberingSystem]);
        }
        // Localize symbols.
        $replacements = [
            '.' => $numberFormat->getDecimalSeparator(),
            ',' => $numberFormat->getGroupingSeparator(),
            '+' => $numberFormat->getPlusSign(),
            '-' => $numberFormat->getMinusSign(),
            '%' => $numberFormat->getPercentSign(),
        ];
        $number = strtr($number, $replacements);

        return $number;
    }

    /**
     * Gets the number format for the provided locale.
     *
     * @param string $locale The locale.
     *
     * @return NumberFormat
     */
    protected function getNumberFormat($locale)
    {
        if (!isset($this->numberFormats[$locale])) {
            $this->numberFormats[$locale] = $this->numberFormatRepository->get($locale);
        }

        return $this->numberFormats[$locale];
    }

    /**
     * Gets the pattern for the provided number format.
     *
     * @param NumberFormat $numberFormat The number format.
     *
     * @return ParsedPattern
     */
    protected function getParsedPattern(NumberFormat $numberFormat, $style)
    {
        $locale = $numberFormat->getLocale();
        if (!isset($this->parsedPatterns[$locale][$style])) {
            $availablePatterns = [
                self::DECIMAL => $numberFormat->getDecimalPattern(),
                self::PERCENT => $numberFormat->getPercentPattern(),
                self::CURRENCY => $numberFormat->getCurrencyPattern(),
                self::CURRENCY_ACCOUNTING => $numberFormat->getAccountingCurrencyPattern(),
            ];
            $this->parsedPatterns[$locale][$style] = new ParsedPattern($availablePatterns[$style]);
        }

        return $this->parsedPatterns[$locale][$style];
    }

    /**
     * {@inheritdoc}
     */
    public function getMinimumFractionDigits()
    {
        return $this->minimumFractionDigits;
    }

    /**
     * {@inheritdoc}
     */
    public function setMinimumFractionDigits($minimumFractionDigits)
    {
        $this->minimumFractionDigits = $minimumFractionDigits;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMaximumFractionDigits()
    {
        return $this->maximumFractionDigits;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaximumFractionDigits($maximumFractionDigits)
    {
        $this->maximumFractionDigits = $maximumFractionDigits;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isGroupingUsed()
    {
        return $this->groupingUsed;
    }

    /**
     * {@inheritdoc}
     */
    public function setGroupingUsed($groupingUsed)
    {
        $this->groupingUsed = $groupingUsed;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyDisplay()
    {
        return $this->currencyDisplay;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyDisplay($currencyDisplay)
    {
        $this->currencyDisplay = $currencyDisplay;

        return $this;
    }
}
