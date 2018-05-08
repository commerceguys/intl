<?php

namespace CommerceGuys\Intl\Formatter;

use CommerceGuys\Intl\Currency\Currency;

interface CurrencyFormatterInterface
{
    /* Formatting style constants */
    const STYLE_STANDARD = 'standard';
    const STYLE_ACCOUNTING = 'accounting';

    /* Currency display style constants */
    const CURRENCY_DISPLAY_NONE = 'none';
    const CURRENCY_DISPLAY_SYMBOL = 'symbol';
    const CURRENCY_DISPLAY_CODE = 'code';

    /**
     * Formats a currency amount.
     *
     * Please note that the provided number should already be rounded.
     * This formatter doesn't do any rounding of its own, and will simply
     * truncate extra digits.
     *
     * @param string   $number   The number.
     * @param Currency $currency The currency.
     * @param string   $locale   The locale (i.e. fr-FR).
     *
     * @return string
     */
    public function format($number, Currency $currency, $locale = null);

    /**
     * Parses a formatted currency amount.
     *
     * @param string   $number   The number.
     * @param Currency $currency The currency.
     * @param string   $locale   The locale (i.e. fr-FR).
     *
     * @return string|false The parsed number or FALSE on error.
     */
    public function parse($number, Currency $currency, $locale = null);

    /**
     * Gets the formatting style.
     *
     * Defaults to self::STYLE_STANDARD.
     *
     * @return string
     */
    public function getStyle();

    /**
     * Sets the formatting style.
     *
     * @param string $style The style, one of the STYLE_ constants.
     *
     * @return self
     */
    public function setStyle($style);

    /**
     * Gets the minimum number of fraction digits.
     *
     * When null, the currency's number of fraction digits will be used instead.
     *
     * @return int|null
     */
    public function getMinimumFractionDigits();

    /**
     * Sets the minimum number of fraction digits.
     *
     * @param int $minimumFractionDigits
     *
     * @return self
     */
    public function setMinimumFractionDigits($minimumFractionDigits);

    /**
     * Gets the maximum number of fraction digits.
     *
     * When null, the currency's number of fraction digits will be used instead.
     *
     * @return int|null
     */
    public function getMaximumFractionDigits();

    /**
     * Sets the maximum number of fraction digits.
     *
     * @param int $maximumFractionDigits
     *
     * @return self
     */
    public function setMaximumFractionDigits($maximumFractionDigits);

    /**
     * Gets whether the major digits will be grouped.
     *
     * @return bool
     */
    public function isGroupingUsed();

    /**
     * Sets whether or not major digits should be grouped.
     *
     * @param bool $groupingUsed
     *
     * @return self
     */
    public function setGroupingUsed($groupingUsed);

    /**
     * Gets the currency display style.
     *
     * Controls whether a currency amount will be shown
     * without with the currency (CURRENCY_DISPLAY_NONE),
     * with the currency symbol (CURRENCY_DISPLAY_SYMBOL)
     * or the currency code (CURRENCY_DISPLAY_CODE).
     *
     * @return string
     */
    public function getCurrencyDisplay();

    /**
     * Sets the currency display style.
     *
     * @param int $currencyDisplay One of the CURRENCY_DISPLAY_ constants.
     *
     * @return string
     */
    public function setCurrencyDisplay($currencyDisplay);
}
