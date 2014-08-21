<?php

namespace CommerceGuys\Intl\Formatter;

use CommerceGuys\Intl\Currency\CurrencyInterface;
use CommerceGuys\Intl\NumberFormat\NumberFormatInterface;
use CommerceGuys\Intl\NumberFormat\NumberFormat;

/**
 * Formats numbers using locale-specific patterns.
 */
class NumberFormatter implements NumberFormatterInterface
{
    /**
     * The number format.
     *
     * @var \CommerceGuys\NumberFormat\NumberFormatInterface
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
     * @var integer
     */
    protected $primaryGroupSize;

    /**
     * The size of every group of digits after the primary group.
     *
     * @var integer
     */
    protected $secondaryGroupSize;

    /**
     * The minimum number of fraction digits to show.
     *
     * @var integer
     */
    protected $minimumFractionDigits;

    /**
     * The maximum number of fraction digits to show.
     *
     * @var integer
     */
    protected $maximumFractionDigits;

    /**
     * The currency display style.
     *
     * @var integer
     */
    protected $currencyDisplay;

    public function __construct(NumberFormatInterface $numberFormat, $style = self::DECIMAL)
    {
        $availablePatterns = array(
            self::DECIMAL => $numberFormat->getDecimalPattern(),
            self::PERCENT => $numberFormat->getPercentPattern(),
            self::CURRENCY => $numberFormat->getCurrencyPattern(),
            self::CURRENCY_ACCOUNTING => $numberFormat->getAccountingCurrencyPattern(),
        );
        if (!isset($availablePatterns[$style])) {
            // Unknown type.
            throw new InvalidArgumentException('Unknown format style provided to DecimalFormatter::__construct().');
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
        $this->groupingUsed = (strpos($this->positivePattern, ',') !== FALSE);
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
        if (in_array($style, array(self::DECIMAL, self::PERCENT))) {
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
            $groups = array();
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
        $value = $minorDigits ? $majorDigits . '.' . $minorDigits : $majorDigits;
        $pattern = $negative ? $this->negativePattern : $this->positivePattern;
        $value = preg_replace('/#(?:[\.,]#+)*0(?:[,\.][0#]+)*/', $value, $pattern);

        return $this->replaceSymbols($value);
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
        }
        else {
            $symbol = $currency->getCurrencyCode();
        }

        return str_replace('¤', $symbol, $value);
    }

    /**
     * Replaces number symbols with their localized equivalents.
     *
     * @param string $value The value being formatted.
     *
     * @see http://cldr.unicode.org/translation/number-symbols
     */
    protected function replaceSymbols($value)
    {
        $replacements = array(
           '.' => $this->numberFormat->getDecimalSeparator(),
           ',' => $this->numberFormat->getGroupingSeparator(),
           '+' => $this->numberFormat->getPlusSign(),
           '-' => $this->numberFormat->getMinusSign(),
           '%' => $this->numberFormat->getPercentSign(),
        );

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
    }

    /**
     * {@inheritdoc}
     */
    public function getMaximumFractionDigits() {
        return $this->maximumFractionDigits;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaximumFractionDigits($maximumFractionDigits)
    {
        $this->maximumFractionDigits = $maximumFractionDigits;
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
    }
}
