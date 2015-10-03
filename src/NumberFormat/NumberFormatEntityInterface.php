<?php

namespace CommerceGuys\Intl\NumberFormat;

interface NumberFormatEntityInterface extends NumberFormatInterface
{
    /**
     * Sets the locale.
     *
     * @param string $locale The locale (i.e. "en_US").
     *
     * @return self
     */
    public function setLocale($locale);

    /**
     * Sets the numbering system.
     *
     * @param string $numberingSystem One of the NUMBERING_SYSTEM_ constants.
     *
     * @return self
     */
    public function setNumberingSystem($numberingSystem);

    /**
     * Sets the decimal separator.
     *
     * @param string $decimalSeparator
     *
     * @return self
     */
    public function setDecimalSeparator($decimalSeparator);

    /**
     * Sets the grouping separator.
     *
     * @param string $groupingSeparator
     *
     * @return self
     */
    public function setGroupingSeparator($groupingSeparator);

    /**
     * Sets the plus sign.
     *
     * @param string $plusSign
     *
     * @return self
     */
    public function setPlusSign($plusSign);

    /**
     * Sets the minus sign.
     *
     * @param string $minusSign
     *
     * @return self
     */
    public function setMinusSign($minusSign);

    /**
     * Sets the percent sign.
     *
     * @param string $percentSign
     *
     * @return self
     */
    public function setPercentSign($percentSign);

    /**
     * Sets the number pattern used to format decimal numbers.
     *
     * @param string $decimalPattern The decimal pattern.
     *
     * @return self
     */
    public function setDecimalPattern($decimalPattern);

    /**
     * Sets the number pattern used to format percentages.
     *
     * @param string $percentPattern The percent pattern.
     *
     * @return self
     */
    public function setPercentPattern($percentPattern);

    /**
     * Sets the number pattern used to format currency amounts.
     *
     * @param string $currencyPattern The currency pattern.
     *
     * @return self
     */
    public function setCurrencyPattern($currencyPattern);

    /**
     * Sets the number pattern used to format accounting currency amounts.
     *
     * Most commonly used when formatting amounts on invoices.
     *
     * @param string $accountingCurrencyPattern The accounting currency pattern.
     *
     * @return self
     */
    public function setAccountingCurrencyPattern($accountingCurrencyPattern);
}
