<?php

namespace CommerceGuys\Intl\NumberFormat;

interface NumberFormatInterface
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
     * Gets the locale.
     *
     * @return string
     */
    public function getLocale();

    /**
     * Sets the locale.
     *
     * @param string $locale The locale (i.e. "en_US").
     */
    public function setLocale($locale);

    /**
     * Gets the numbering system.
     *
     * The value is one of the NUMBERING_SYSTEM_ constants.
     *
     * @return string
     */
    public function getNumberingSystem();

    /**
     * Sets the numbering system.
     *
     * @param string $numberingSystem One of the NUMBERING_SYSTEM_ constants.
     */
    public function setNumberingSystem($numberingSystem);

    /**
     * Gets the decimal separator.
     *
     * @return string
     */
    public function getDecimalSeparator();

    /**
     * Sets the decimal separator.
     *
     * @var string $decimalSeparator
     */
    public function setDecimalSeparator($decimalSeparator);

    /**
     * Gets the grouping separator.
     *
     * @return string
     */
    public function getGroupingSeparator();

    /**
     * Sets the grouping separator.
     *
     * @var string $groupingSeparator
     */
    public function setGroupingSeparator($groupingSeparator);

    /**
     * Gets the plus sign.
     *
     * @return string
     */
    public function getPlusSign();

    /**
     * Sets the plus sign.
     *
     * @var string $plusSign
     */
    public function setPlusSign($plusSign);

    /**
     * Gets the minus sign.
     *
     * @return string
     */
    public function getMinusSign();

    /**
     * Sets the minus sign.
     *
     * @var string $minusSign
     */
    public function setMinusSign($minusSign);

    /**
     * Gets the percent sign.
     *
     * @return string
     */
    public function getPercentSign();

    /**
     * Sets the percent sign.
     *
     * @var string $percentSign
     */
    public function setPercentSign($percentSign);

    /**
     * Gets the number pattern used to format decimal numbers.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getDecimalPattern();

    /**
     * Sets the number pattern used to format decimal numbers.
     *
     * @param string $decimalPattern The decimal pattern.
     */
    public function setDecimalPattern($decimalPattern);

    /**
     * Gets the number pattern used to format percentages.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getPercentPattern();

    /**
     * Sets the number pattern used to format percentages.
     *
     * @param string $percentPattern The percent pattern.
     */
    public function setPercentPattern($percentPattern);

    /**
     * Gets the number pattern used to format currency amounts.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getCurrencyPattern();

    /**
     * Sets the number pattern used to format currency amounts.
     *
     * @param string $currencyPattern The currency pattern.
     */
    public function setCurrencyPattern($currencyPattern);

    /**
     * Gets the number pattern used to format accounting currency amounts.
     *
     * Most commonly used when formatting amounts on invoices.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getAccountingCurrencyPattern();

    /**
     * Sets the number pattern used to format accounting currency amounts.
     *
     * Most commonly used when formatting amounts on invoices.
     *
     * @param string $accountingCurrencyPattern The accounting currency pattern.
     */
    public function setAccountingCurrencyPattern($accountingCurrencyPattern);
}
