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
    protected string $currencyCode;

    /**
     * The currency name.
     *
     * @var string
     */
    protected string $name;

    /**
     * The numeric currency code.
     *
     * @var string
     */
    protected string $numericCode;

    /**
     * The currency symbol.
     *
     * @var string
     */
    protected string $symbol;

    /**
     * The number of fraction digits.
     *
     * @var int
     */
    protected int $fractionDigits = 2;

    /**
     * The number of fraction digits used for display, if different.
     *
     * @var int|null
     */
    protected ?int $displayFractionDigits = null;

    /**
     * The locale (i.e. "en_US").
     *
     * @var string
     */
    protected string $locale;

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
        if (isset($definition['display_fraction_digits'])) {
            $this->displayFractionDigits = $definition['display_fraction_digits'];
        }
        $this->locale = $definition['locale'];
    }

    /**
     * Gets the string representation of the currency.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->currencyCode;
    }

    /**
     * Gets the alphabetic currency code.
     *
     * @return string
     */
    public function getCurrencyCode(): string
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
    public function getName(): string
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
    public function getNumericCode(): string
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
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * Gets the number of fraction digits.
     *
     * Matches the ISO 4217 minor units. Used when rounding an amount, or
     * converting it to minor units (e.g. for payment gateways).
     * Actual storage precision can be greater.
     *
     * @return int
     */
    public function getFractionDigits(): int
    {
        return $this->fractionDigits;
    }

    /**
     * Gets the number of fraction digits used for display.
     *
     * Matches CLDR, which can differ from the ISO 4217 minor units when
     * the minor unit isn't used in practice (e.g. HUF is displayed without
     * decimals). Used as the default when formatting an amount.
     *
     * @return int
     */
    public function getDisplayFractionDigits(): int
    {
        return $this->displayFractionDigits ?? $this->fractionDigits;
    }

    /**
     * Gets the locale.
     *
     * The currency name and symbol are locale specific.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
}
