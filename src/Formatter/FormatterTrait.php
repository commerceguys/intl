<?php

namespace CommerceGuys\Intl\Formatter;

use CommerceGuys\Intl\Calculator;
use CommerceGuys\Intl\Exception\InvalidArgumentException;
use CommerceGuys\Intl\NumberFormat\NumberFormat;

trait FormatterTrait
{
    /**
     * The parsed number patterns, keyed by locale and style.
     *
     * @var ParsedPattern[]
     */
    protected array $parsedPatterns = [];

    /**
     * Localized digits.
     *
     * @var array
     */
    protected array $digits = [
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
     * Formats the number according to the number format.
     *
     * @param string       $number       The number.
     * @param NumberFormat $numberFormat The number format.
     *
     * @return string The formatted number.
     */
    protected function formatNumber(string $number, NumberFormat $numberFormat, array $options = []): string
    {
        $parsedPattern = $this->getParsedPattern($numberFormat, $options['style']);
        // Start by rounding the number, if rounding is enabled.
        if (is_int($options['rounding_mode'])) {
            $number = Calculator::round($number, $options['maximum_fraction_digits'], $options['rounding_mode']);
        }
        $negative = (Calculator::compare('0', $number, 12) == 1);
        // Ensure that the value is positive and has the right number of digits.
        $signMultiplier = $negative ? '-1' : '1';
        $number = bcdiv($number, $signMultiplier, $options['maximum_fraction_digits']);
        // Split the number into major and minor digits.
        $numberParts = explode('.', $number);
        $majorDigits = $numberParts[0];
        // Account for maximumFractionDigits = 0, where the number won't
        // have a decimal point, and $numberParts[1] won't be set.
        $minorDigits = isset($numberParts[1]) ? $numberParts[1] : '';

        if ($options['use_grouping'] && $parsedPattern->isGroupingUsed()) {
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

        if ($options['minimum_fraction_digits'] < $options['maximum_fraction_digits']) {
            // Strip any trailing zeroes.
            $minorDigits = rtrim($minorDigits, '0');
            if (strlen($minorDigits) < $options['minimum_fraction_digits']) {
                // Now there are too few digits, re-add trailing zeroes
                // until the desired length is reached.
                $neededZeroes = $options['minimum_fraction_digits'] - strlen($minorDigits);
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
    protected function localizeNumber(string $number, NumberFormat $numberFormat): string
    {
        // Localize digits.
        $numberingSystem = $numberFormat->getNumberingSystem();
        if (isset($this->digits[$numberingSystem])) {
            $number = strtr($number, $this->digits[$numberingSystem]);
        }
        // Localize symbols.
        $replacements = $this->getLocalizedSymbols($numberFormat);
        $number = strtr($number, $replacements);

        return $number;
    }

    /**
     * Parses the number according to the number format.
     *
     * Both the digits and the symbols are replaced
     * with their non-localized equivalents.
     *
     * @param string       $number       The number.
     * @param NumberFormat $numberFormat The number format.
     *
     * @return string|bool The localized number, or FALSE on error.
     */
    protected function parseNumber(string $number, NumberFormat $numberFormat): string|bool
    {
        // Convert localized symbols back to their original form.
        $replacements = array_flip($this->getLocalizedSymbols($numberFormat));
        // Strip whitespace (spaces and non-breaking spaces).
        $replacements += [
            ' ' => '',
            chr(0xC2) . chr(0xA0) => '',
        ];
        $numberingSystem = $numberFormat->getNumberingSystem();
        if (isset($this->digits[$numberingSystem])) {
            // Convert the localized digits back to latin.
            $replacements += array_flip($this->digits[$numberingSystem]);
        }
        $number = strtr($number, $replacements);

        // Strip grouping separators.
        $number = str_replace(',', '', $number);
        // Convert the accounting format for negative numbers.
        if (substr($number, 0, 1) == '(' && substr($number, -1, 1) == ')') {
            $number = '-' . str_replace(['(', ')'], '', $number);
        }
        // Convert percentages back to their decimal form.
        if (strpos($number, '%') !== false) {
            $number = str_replace('%', '', $number);
            $number = Calculator::divide($number, '100');
        }

        return is_numeric($number) ? $number : false;
    }

    /**
     * Gets the pattern for the provided number format.
     *
     * @param NumberFormat $numberFormat The number format.
     * @param string       $style        The formatter style.
     *
     * @return ParsedPattern
     *
     * @throws InvalidArgumentException
     */
    protected function getParsedPattern(NumberFormat $numberFormat, string $style): ParsedPattern
    {
        $locale = $numberFormat->getLocale();
        if (!isset($this->parsedPatterns[$locale][$style])) {
            $availablePatterns = $this->getAvailablePatterns($numberFormat);
            if (!isset($availablePatterns[$style])) {
                throw new InvalidArgumentException(sprintf('Unrecognized style "%s".', $style));
            }

            $this->parsedPatterns[$locale][$style] = new ParsedPattern($availablePatterns[$style]);
        }

        return $this->parsedPatterns[$locale][$style];
    }

    /**
     * Gets the available patterns for the provided number format.
     *
     * @param NumberFormat $numberFormat The number format.
     *
     * @return string[] The patterns, keyed by style.
     */
    abstract protected function getAvailablePatterns(NumberFormat $numberFormat): array;

    /**
     * Gets the localized symbols for the provided number format.
     *
     * Used to localize the number in localizeNumber().
     *
     * @param NumberFormat $numberFormat The number format.
     *
     * @return array
     */
    abstract protected function getLocalizedSymbols(NumberFormat $numberFormat): array;
}
