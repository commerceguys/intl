<?php

namespace CommerceGuys\Intl\NumberFormat;

/**
 * Provides metadata for number formatting.
 */
final class NumberFormat
{
    // Arabic-Indic digits.
    const NUMBERING_SYSTEM_ARABIC = 'arab';
    // Extended Arabic-Indic digits.
    const NUMBERING_SYSTEM_ARABIC_EXTENDED = 'arabext';
    // Bengali digits.
    const NUMBERING_SYSTEM_BENGALI = 'beng';
    // Devanagari digits.
    const NUMBERING_SYSTEM_DEVANAGARI = 'deva';
    // Latin digits
    const NUMBERING_SYSTEM_LATIN = 'latn';

    /**
     * The locale (i.e. "en_US").
     *
     * @var string
     */
    protected $locale;

    /**
     * The number pattern used to format decimal numbers.
     *
     * @var string
     */
    protected $decimalPattern;

    /**
     * The number pattern used to format percentages.
     *
     * @var string
     */
    protected $percentPattern;

    /**
     * The number pattern used to format currency amounts.
     *
     * @var string
     */
    protected $currencyPattern;

    /**
     * The number pattern used to format accounting currency amounts.
     *
     * @var string
     */
    protected $accountingCurrencyPattern;

    /**
     * The numbering system.
     *
     * @var string
     */
    protected $numberingSystem = self::NUMBERING_SYSTEM_LATIN;

    /**
     * The decimal separator.
     *
     * @var string
     */
    protected $decimalSeparator = '.';

    /**
     * The decimal separator for currency amounts.
     *
     * @var string
     */
    protected $decimalCurrencySeparator = '.';

    /**
     * The grouping separator.
     *
     * @var string
     */
    protected $groupingSeparator = ',';

    /**
     * The grouping separator for currency amounts.
     *
     * @var string
     */
    protected $groupingCurrencySeparator = ',';

    /**
     * The plus sign.
     *
     * @var string
     */
    protected $plusSign = '+';

    /**
     * The number symbols.
     *
     * @var string
     */
    protected $minusSign = '-';

    /**
     * The percent sign.
     *
     * @var string
     */
    protected $percentSign = '%';

    /**
     * Creates a new NumberFormat instance.
     *
     * @param array $definition The definition array.
     */
    public function __construct(array $definition)
    {
        // Validate the presence of required properties.
        $requiredProperties = [
            'locale', 'decimal_pattern', 'percent_pattern',
            'currency_pattern', 'accounting_currency_pattern',
        ];
        foreach ($requiredProperties as $requiredProperty) {
            if (empty($definition[$requiredProperty])) {
                throw new \InvalidArgumentException(sprintf('Missing required property "%s".', $requiredProperty));
            }
        }
        // Validate the numbering system.
        if (isset($definition['numbering_system'])) {
            if (!in_array($definition['numbering_system'], ['arab', 'arabext', 'beng', 'deva', 'latn'])) {
                throw new \InvalidArgumentException(sprintf('Invalid numbering system "%s".', $definition['numbering_system']));
            }
        }

        $this->locale = $definition['locale'];
        $this->decimalPattern = $definition['decimal_pattern'];
        $this->percentPattern = $definition['percent_pattern'];
        $this->currencyPattern = $definition['currency_pattern'];
        $this->accountingCurrencyPattern = $definition['accounting_currency_pattern'];
        if (isset($definition['numbering_system'])) {
            $this->numberingSystem = $definition['numbering_system'];
        }
        if (isset($definition['decimal_separator'])) {
            $this->decimalSeparator = $definition['decimal_separator'];
        }
        if (isset($definition['decimal_currency_separator'])) {
            $this->decimalCurrencySeparator = $definition['decimal_currency_separator'];
        } else {
            $this->decimalCurrencySeparator = $this->decimalSeparator;
        }
        if (isset($definition['grouping_separator'])) {
            $this->groupingSeparator = $definition['grouping_separator'];
        }
        if (isset($definition['grouping_currency_separator'])) {
            $this->groupingCurrencySeparator = $definition['grouping_currency_separator'];
        } else {
            $this->groupingCurrencySeparator = $this->groupingSeparator;
        }
        if (isset($definition['plus_sign'])) {
            $this->plusSign = $definition['plus_sign'];
        }
        if (isset($definition['minus_sign'])) {
            $this->minusSign = $definition['minus_sign'];
        }
        if (isset($definition['percent_sign'])) {
            $this->percentSign = $definition['percent_sign'];
        }
    }

    /**
     * Gets the locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Gets the number pattern used to format decimal numbers.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getDecimalPattern()
    {
        return $this->decimalPattern;
    }

    /**
     * Gets the number pattern used to format percentages.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getPercentPattern()
    {
        return $this->percentPattern;
    }

    /**
     * Gets the number pattern used to format currency amounts.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getCurrencyPattern()
    {
        return $this->currencyPattern;
    }

    /**
     * Gets the number pattern used to format accounting currency amounts.
     *
     * Most commonly used when formatting amounts on invoices.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getAccountingCurrencyPattern()
    {
        return $this->accountingCurrencyPattern;
    }

    /**
     * Gets the numbering system.
     *
     * The value is one of the NUMBERING_SYSTEM_ constants.
     *
     * @return string
     */
    public function getNumberingSystem()
    {
        return $this->numberingSystem;
    }

    /**
     * Gets the decimal separator.
     *
     * @return string
     */
    public function getDecimalSeparator()
    {
        return $this->decimalSeparator;
    }

    /**
     * Gets the decimal separator for currency amounts.
     *
     * @return string
     */
    public function getDecimalCurrencySeparator()
    {
        return $this->decimalCurrencySeparator;
    }

    /**
     * Gets the grouping separator for currency amounts.
     *
     * @return string
     */
    public function getGroupingSeparator()
    {
        return $this->groupingSeparator;
    }

    /**
     * Gets the currency grouping separator.
     *
     * @return string
     */
    public function getGroupingCurrencySeparator()
    {
        return $this->groupingCurrencySeparator;
    }

    /**
     * Gets the plus sign.
     *
     * @return string
     */
    public function getPlusSign()
    {
        return $this->plusSign;
    }

    /**
     * Gets the minus sign.
     *
     * @return string
     */
    public function getMinusSign()
    {
        return $this->minusSign;
    }

    /**
     * Gets the percent sign.
     *
     * @return string
     */
    public function getPercentSign()
    {
        return $this->percentSign;
    }
}
