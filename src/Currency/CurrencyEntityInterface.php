<?php

namespace CommerceGuys\Intl\Currency;

interface CurrencyEntityInterface extends CurrencyInterface
{
    /**
     * Sets the alphabetic currency code.
     *
     * @param string $currencyCode The alphabetic currency code.
     */
    public function setCurrencyCode($currencyCode);

    /**
     * Sets the currency name.
     *
     * @param string $name The currency name.
     */
    public function setName($name);

    /**
     * Sets the numeric currency code.
     *
     * @param string $numericCode The numeric currency code.
     */
    public function setNumericCode($numericCode);

    /**
     * Sets the currency symbol.
     *
     * @param string $symbol The currency symbol.
     */
    public function setSymbol($symbol);

    /**
     * Sets the number of fraction digits.
     *
     * @param int $fractionDigits The number of fraction digits.
     */
    public function setFractionDigits($fractionDigits);
}
