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
     * Gets the currency name.
     *
     * @return string
     */
    public function getName();

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
     * Gets the currency symbol.
     *
     * @return string
     */
    public function getSymbol();

    /**
     * Gets the number of fraction digits.
     *
     * Used when rounding or formatting an amount for display.
     * Actual storage precision can be greater.
     *
     * @return int
     */
    public function getFractionDigits();
}
