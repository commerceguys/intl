<?php

namespace CommerceGuys\Intl\Formatter;

interface CurrencyFormatterInterface
{
    /* Formatting style constants */
    const STYLE_STANDARD = 'standard';
    const STYLE_ACCOUNTING = 'accounting';

    /* Rounding mode constants */
    const ROUND_NONE = 0;
    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;
    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;
    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;
    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    /* Currency display style constants */
    const CURRENCY_DISPLAY_NONE = 'none';
    const CURRENCY_DISPLAY_SYMBOL = 'symbol';
    const CURRENCY_DISPLAY_CODE = 'code';

    /**
     * Formats a currency amount.
     *
     * @param string $number       The number.
     * @param string $currencyCode The currency code.
     * @param string $locale       The locale (i.e. fr-FR).
     *
     * @return string
     */
    public function format($number, $currencyCode, $locale = null);

    /**
     * Parses a formatted currency amount.
     *
     * @param string $number       The number.
     * @param string $currencyCode The currency code.
     * @param string $locale       The locale (i.e. fr-FR).
     *
     * @return string|false The parsed number or FALSE on error.
     */
    public function parse($number, $currencyCode, $locale = null);

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
     * Gets the rounding mode.
     *
     * @return int
     */
    public function getRoundingMode();

    /**
     * Sets the rounding mode.
     *
     * @param int $roundingMode The rounding mode. One of the ROUND_ constants.
     *
     * @return self
     */
    public function setRoundingMode($roundingMode);

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
