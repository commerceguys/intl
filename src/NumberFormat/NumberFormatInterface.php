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
     * Gets the numbering system.
     *
     * The value is one of the NUMBERING_SYSTEM_ constants.
     *
     * @return string
     */
    public function getNumberingSystem();

    /**
     * Gets the decimal separator.
     *
     * @return string
     */
    public function getDecimalSeparator();

    /**
     * Gets the grouping separator.
     *
     * @return string
     */
    public function getGroupingSeparator();

    /**
     * Gets the plus sign.
     *
     * @return string
     */
    public function getPlusSign();

    /**
     * Gets the minus sign.
     *
     * @return string
     */
    public function getMinusSign();

    /**
     * Gets the percent sign.
     *
     * @return string
     */
    public function getPercentSign();

    /**
     * Gets the number pattern used to format decimal numbers.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getDecimalPattern();

    /**
     * Gets the number pattern used to format percentages.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getPercentPattern();

    /**
     * Gets the number pattern used to format currency amounts.
     *
     * @return string
     *
     * @see http://cldr.unicode.org/translation/number-patterns
     */
    public function getCurrencyPattern();

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
}
