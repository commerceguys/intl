<?php

namespace CommerceGuys\Intl\Formatter;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\NumberFormat\NumberFormat;

interface NumberFormatterInterface
{
    /* Formatting style constants */
    const STYLE_DECIMAL = 'decimal';
    const STYLE_PERCENT = 'percent';

    /* Rounding mode constants */
    const ROUND_NONE = 0;
    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;
    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;
    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;
    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    /**
     * Formats a number.
     *
     * @param string $number The number.
     * @param string $locale The locale (i.e. fr-FR).
     *
     * @return string
     */
    public function format($number, $locale = null);

    /**
     * Parses a number.
     *
     * Commonly used in input widgets where the end-user might input
     * a number using digits and symbols common to their locale.
     *
     * @param string $number The number.
     * @param string $locale The locale (i.e. fr-FR).
     *
     * @return string|false The parsed number or FALSE on error.
     */
    public function parse($number, $locale = null);

    /**
     * Gets the formatting style.
     *
     * Defaults to self::STYLE_DECIMAL.
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
     * @return int
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
     * @return int
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
}
