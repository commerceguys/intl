<?php

namespace CommerceGuys\Intl\NumberFormat;

interface NumberFormatEntityInterface extends NumberFormatInterface
{
    /**
     * Sets the locale.
     *
     * @param string $locale The locale (i.e. "en_US").
     */
    public function setLocale($locale);

    /**
     * Sets the numbering system.
     *
     * @param string $numberingSystem One of the NUMBERING_SYSTEM_ constants.
     */
    public function setNumberingSystem($numberingSystem);

    /**
     * Sets the decimal separator.
     *
     * @param string $decimalSeparator
     */
    public function setDecimalSeparator($decimalSeparator);

    /**
     * Sets the grouping separator.
     *
     * @param string $groupingSeparator
     */
    public function setGroupingSeparator($groupingSeparator);

    /**
     * Sets the plus sign.
     *
     * @param string $plusSign
     */
    public function setPlusSign($plusSign);

    /**
     * Sets the minus sign.
     *
     * @param string $minusSign
     */
    public function setMinusSign($minusSign);

    /**
     * Sets the percent sign.
     *
     * @param string $percentSign
     */
    public function setPercentSign($percentSign);

    /**
     * Sets the number pattern used to format decimal numbers.
     *
     * @param string $decimalPattern The decimal pattern.
     */
    public function setDecimalPattern($decimalPattern);

    /**
     * Sets the number pattern used to format percentages.
     *
     * @param string $percentPattern The percent pattern.
     */
    public function setPercentPattern($percentPattern);

    /**
     * Sets the number pattern used to format currency amounts.
     *
     * @param string $currencyPattern The currency pattern.
     */
    public function setCurrencyPattern($currencyPattern);

    /**
     * Sets the number pattern used to format accounting currency amounts.
     *
     * Most commonly used when formatting amounts on invoices.
     *
     * @param string $accountingCurrencyPattern The accounting currency pattern.
     */
    public function setAccountingCurrencyPattern($accountingCurrencyPattern);
}
