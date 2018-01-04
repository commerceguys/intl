<?php

namespace CommerceGuys\Intl\Currency;

/**
 * Represents a currency.
 */
final class Currency
{
    /**
     * The alphanumeric currency code.
     *
     * @var string
     */
    protected $currencyCode;

    /**
     * The currency name.
     *
     * @var string
     */
    protected $name;

    /**
     * The numeric currency code.
     *
     * @var string
     */
    protected $numericCode;

    /**
     * The currency symbol.
     *
     * @var string
     */
    protected $symbol;

    /**
     * The number of fraction digits.
     *
     * @var int
     */
    protected $fractionDigits = 2;

    /**
     * The locale (i.e. "en_US").
     *
     * @var string
     */
    protected $locale;

    /**
     * Creates a new Currency instance.
     *
     * @param array $definition The definition array.
     */
    public function __construct(array $definition)
    {
        foreach (['currency_code', 'name', 'numeric_code', 'locale'] as $requiredProperty) {
            if (empty($definition[$requiredProperty])) {
                throw new \InvalidArgumentException(sprintf('Missing required property "%s".', $requiredProperty));
            }
        }
        if (!isset($definition['symbol'])) {
            $definition['symbol'] = $definition['currency_code'];
        }

        $this->currencyCode = $definition['currency_code'];
        $this->name = $definition['name'];
        $this->numericCode = $definition['numeric_code'];
        $this->symbol = $definition['symbol'];
        if (isset($definition['fraction_digits'])) {
            $this->fractionDigits = $definition['fraction_digits'];
        }
        $this->locale = $definition['locale'];
    }

    /**
     * Gets the string representation of the currency.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->currencyCode;
    }

    /**
     * Gets the alphabetic currency code.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Gets the currency name.
     *
     * This value is locale specific.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the numeric currency code.
     *
     * The numeric code has three digits, and the first one can be a zero,
     * hence the need to pass it around as a string.
     *
     * @return string
     */
    public function getNumericCode()
    {
        return $this->numericCode;
    }

    /**
     * Gets the currency symbol.
     *
     * This value is locale specific.
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Gets the number of fraction digits.
     *
     * Used when rounding or formatting an amount for display.
     * Actual storage precision can be greater.
     *
     * @return int
     */
    public function getFractionDigits()
    {
        return $this->fractionDigits;
    }

    /**
     * Gets the locale.
     *
     * The currency name and symbol are locale specific.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
