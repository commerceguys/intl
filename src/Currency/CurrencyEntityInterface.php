<?php

namespace CommerceGuys\Intl\Currency;

interface CurrencyEntityInterface extends CurrencyInterface
{
    /**
     * Sets the alphabetic currency code.
     *
     * @param string $currencyCode The alphabetic currency code.
     *
     * @return self
     */
    public function setCurrencyCode($currencyCode);

    /**
     * Sets the currency name.
     *
     * @param string $name The currency name.
     *
     * @return self
     */
    public function setName($name);

    /**
     * Sets the numeric currency code.
     *
     * @param string $numericCode The numeric currency code.
     *
     * @return self
     */
    public function setNumericCode($numericCode);

    /**
     * Sets the currency symbol.
     *
     * @param string $symbol The currency symbol.
     *
     * @return self
     */
    public function setSymbol($symbol);

    /**
     * Sets the number of fraction digits.
     *
     * @param int $fractionDigits The number of fraction digits.
     *
     * @return self
     */
    public function setFractionDigits($fractionDigits);
}
