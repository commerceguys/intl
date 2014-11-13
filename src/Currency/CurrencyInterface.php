<?php

namespace CommerceGuys\Intl\Currency;

interface CurrencyInterface
{
    /**
     * Gets the alphabetic currency code.
     *
     * @return string
     */
    public function getCurrencyCode();

    /**
     * Sets the alphabetic currency code.
     *
     * @param string $currencyCode The alphabetic currency code.
     */
    public function setCurrencyCode($currencyCode);

    /**
     * Gets the currency name.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the currency name.
     *
     * @param string $name The currency name.
     */
    public function setName($name);

    /**
     * Gets the numeric currency code.
     *
     * The numeric code has three digits, and the first one can be a zero,
     * hence the need to pass it around as a string.
     *
     * @return string
     */
    public function getNumericCode();

    /**
     * Sets the numeric currency code.
     *
     * @param string $numericCode The numeric currency code.
     */
    public function setNumericCode($numericCode);

    /**
     * Gets the currency symbol.
     *
     * @return string
     */
    public function getSymbol();

    /**
     * Sets the currency symbol.
     *
     * @param string $symbol The currency symbol.
     */
    public function setSymbol($symbol);

    /**
     * Gets the number of fraction digits.
     *
     * Used when rounding or formatting an amount for display.
     * Actual storage precision can be greater.
     *
     * @return int
     */
    public function getFractionDigits();

    /**
     * Sets the number of fraction digits.
     *
     * @param int $fractionDigits The number of fraction digits.
     */
    public function setFractionDigits($fractionDigits);
}
