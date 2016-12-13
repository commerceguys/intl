<?php

namespace CommerceGuys\Intl\Formatter;

use CommerceGuys\Intl\Currency\CurrencyInterface;
use CommerceGuys\Intl\Exception\InvalidArgumentException;
use CommerceGuys\Intl\NumberFormat\NumberFormatInterface;

/**
 * Formats numbers using locale-specific patterns.
 */
class NumberFormatter implements NumberFormatterInterface
{
    /**
     * The number format.
     *
     * @var NumberFormatInterface
     */
    protected $numberFormat;

    /**
     * The number pattern used to format positive numbers.
     *
     * @var string
     */
    protected $positivePattern;

    /**
     * The number pattern used to format negative numbers.
     *
     * @var string
     */
    protected $negativePattern;

    /**
     * Whether grouping is used.
     *
     * @var bool
     */
    protected $groupingUsed;

    /**
     * The size of the group of digits closest to the decimal point.
     *
     * @var int
     */
    protected $primaryGroupSize;

    /**
     * The size of every group of digits after the primary group.
     *
     * @var int
     */
    protected $secondaryGroupSize;

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
        NumberFormatInterface::NUMBERING_SYSTEM_ARABIC => [
            0 => '٠', 1 => '١', 2 => '٢', 3 => '٣', 4 => '٤',
            5 => '٥', 6 => '٦', 7 => '٧', 8 => '٨', 9 => '٩',
        ],
        NumberFormatInterface::NUMBERING_SYSTEM_ARABIC_EXTENDED => [
            0 => '۰', 1 => '۱', 2 => '۲', 3 => '۳', 4 => '۴',
            5 => '۵', 6 => '۶', 7 => '۷', 8 => '۸', 9 => '۹',
        ],
        NumberFormatInterface::NUMBERING_SYSTEM_BENGALI => [
            0 => '০', 1 => '১', 2 => '২', 3 => '৩', 4 => '৪',
            5 => '৫', 6 => '৬', 7 => '৭', 8 => '৮', 9 => '৯',
        ],
        NumberFormatInterface::NUMBERING_SYSTEM_DEVANAGARI => [
            0 => '०', 1 => '१', 2 => '२', 3 => '३', 4 => '४',
            5 => '५', 6 => '६', 7 => '७', 8 => '८', 9 => '९',
        ],
    ];

    /**
     * Creaes a NumberFormatter instance.
     *
     * @param NumberFormatInterface $numberFormat The number format.
     * @param int                   $style        The formatting style.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct(NumberFormatInterface $numberFormat, $style = self::DECIMAL)
    {
        if (!extension_loaded('bcmath')) {
            throw new \RuntimeException('The bcmath extension is required by NumberFormatter.');
        }
        $availablePatterns = [
            self::DECIMAL => $numberFormat->getDecimalPattern(),
            self::PERCENT => $numberFormat->getPercentPattern(),
            self::CURRENCY => $numberFormat->getCurrencyPattern(),
            self::CURRENCY_ACCOUNTING => $numberFormat->getAccountingCurrencyPattern(),
        ];
        if (!array_key_exists($style, $availablePatterns)) {
            // Unknown type.
            throw new InvalidArgumentException('Unknown format style provided to NumberFormatter::__construct().');
        }

        // Split the selected pattern into positive and negative patterns.
        $patterns = explode(';', $availablePatterns[$style]);
        if (!isset($patterns[1])) {
            // No explicit negative pattern was provided, construct it.
            $patterns[1] = '-' . $patterns[0];
        }

        $this->numberFormat = $numberFormat;
        $this->positivePattern = $patterns[0];
        $this->negativePattern = $patterns[1];
        $this->groupingUsed = (strpos($this->positivePattern, ',') !== false);
        // This pattern has number groups, parse them.
        if ($this->groupingUsed) {
            preg_match('/#+0/', $this->positivePattern, $primaryGroupMatches);
            $this->primaryGroupSize = $this->secondaryGroupSize = strlen($primaryGroupMatches[0]);
            $numberGroups = explode(',', $this->positivePattern);
            if (count($numberGroups) > 2) {
                // This pattern has a distinct secondary group size.
                $this->secondaryGroupSize = strlen($numberGroups[1]);
            }
        }

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
    public function format($value)
    {
        if (!is_numeric($value)) {
            $message = sprintf('The provided value "%s" must be a valid number or numeric string.', $value);
            throw new InvalidArgumentException($message);
        }

        // Ensure that the value is positive and has the right number of digits.
        $negative = (bccomp('0', $value, 12) == 1);
        $signMultiplier = $negative ? '-1' : '1';
        $value = bcdiv($value, $signMultiplier, $this->maximumFractionDigits);
        // Split the number into major and minor digits.
        $valueParts = explode('.', $value);
        $majorDigits = $valueParts[0];
        // Account for maximumFractionDigits = 0, where the number won't
        // have a decimal point, and $valueParts[1] won't be set.
        $minorDigits = isset($valueParts[1]) ? $valueParts[1] : '';

        if ($this->groupingUsed) {
            // Reverse the major digits, since they are grouped from the right.
            $majorDigits = array_reverse(str_split($majorDigits));
            // Group the major digits.
            $groups = [];
            $groups[] = array_splice($majorDigits, 0, $this->primaryGroupSize);
            while (!empty($majorDigits)) {
                $groups[] = array_splice($majorDigits, 0, $this->secondaryGroupSize);
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
        $value = strlen($minorDigits) ? $majorDigits . '.' . $minorDigits : $majorDigits;
        $pattern = $negative ? $this->negativePattern : $this->positivePattern;
        $value = preg_replace('/#(?:[\.,]#+)*0(?:[,\.][0#]+)*/', $value, $pattern);

        // Localize the number.
        $value = $this->replaceDigits($value);
        $value = $this->replaceSymbols($value);

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function formatCurrency($value, CurrencyInterface $currency)
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
        $value = $this->format($value);

        // Reset the fraction digit settings, so that they don't affect
        // future formattings with different currencies.
        if ($resetMinimumFractionDigits) {
            $this->minimumFractionDigits = null;
        }
        if ($resetMaximumFractionDigits) {
            $this->maximumFractionDigits = null;
        }

        // Determine whether to show the currency symbol or the currency code.
        if ($this->currencyDisplay == self::CURRENCY_DISPLAY_SYMBOL) {
            $symbol = $currency->getSymbol();
        } else {
            $symbol = $currency->getCurrencyCode();
        }

        return str_replace('¤', $symbol, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function parse($value)
    {
        $replacements = [
            $this->numberFormat->getGroupingSeparator() => '',
            // Convert the localized symbols back to their original form.
            $this->numberFormat->getDecimalSeparator() => '.',
            $this->numberFormat->getPlusSign() => '+',
            $this->numberFormat->getMinusSign() => '-',

            // Strip whitespace (spaces and non-breaking spaces).
            ' ' => '',
            chr(0xC2) . chr(0xA0) => '',
        ];
        $numberingSystem = $this->numberFormat->getNumberingSystem();
        if (isset($this->digits[$numberingSystem])) {
            // Convert the localized digits back to latin.
            $replacements += array_flip($this->digits[$numberingSystem]);
        }

        $value = strtr($value, $replacements);
        if (substr($value, 0, 1) == '(' && substr($value, -1, 1) == ')') {
            // This is an accounting formatted negative number.
            $value = '-' . str_replace(['(', ')'], '', $value);
        }

        return is_numeric($value) ? $value : false;
    }

    /**
     * {@inheritdoc}
     */
    public function parseCurrency($value, CurrencyInterface $currency)
    {
        $replacements = [
            // Strip the currency code or symbol.
            $currency->getCurrencyCode() => '',
            $currency->getSymbol() => '',
        ];
        $value = strtr($value, $replacements);

        return $this->parse($value);
    }

    /**
     * Replaces digits with their localized equivalents.
     *
     * @param string $value The value being formatted.
     *
     * @return string
     */
    protected function replaceDigits($value)
    {
        $numberingSystem = $this->numberFormat->getNumberingSystem();
        if (isset($this->digits[$numberingSystem])) {
            $value = strtr($value, $this->digits[$numberingSystem]);
        }

        return $value;
    }

    /**
     * Replaces number symbols with their localized equivalents.
     *
     * @param string $value The value being formatted.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-symbols
     */
    protected function replaceSymbols($value)
    {
        $replacements = [
            '.' => $this->numberFormat->getDecimalSeparator(),
            ',' => $this->numberFormat->getGroupingSeparator(),
            '+' => $this->numberFormat->getPlusSign(),
            '-' => $this->numberFormat->getMinusSign(),
            '%' => $this->numberFormat->getPercentSign(),
        ];

        return strtr($value, $replacements);
    }

    /**
     * {@inheritdoc}
     */
    public function getNumberFormat()
    {
        return $this->numberFormat;
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
