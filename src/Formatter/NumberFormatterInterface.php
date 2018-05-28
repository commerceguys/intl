<?php

namespace CommerceGuys\Intl\Formatter;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\NumberFormat\NumberFormat;

interface NumberFormatterInterface
{
    /**
     * Formats a number.
     *
     * Supported options:
     * - locale:                  The locale. Default: 'en'.
     * - use_grouping:            Whether to use grouping separators,
     *                            such as thousands separators.
     *                            Default: true.
     * - minimum_fraction_digits: Minimum fraction digits. Default: 0.
     * - maximum_fraction_digits: Minimum fraction digits. Default: 3.
     * - rounding_mode:           The rounding mode.
     *                            A PHP_ROUND_ constant or 'none' to skip
     *                            rounding. Default: PHP_ROUND_HALF_UP.
     * - style:                   The style.
     *                            One of: 'decimal', 'percent'.
     *                            Default: 'decimal'.
     *
     * @param string $number  The number.
     * @param array  $options The formatting options.
     *
     * @return string The formatted number.
     */
    public function format($number, array $options = []);

    /**
     * Parses a number.
     *
     * Commonly used in input widgets where the end-user might input
     * a number using digits and symbols common to their locale.
     *
     * Supported options:
     * - locale: The locale. Default: 'en'.
     *
     * @param string $number  The formatted number.
     * @param array  $options The parsing options.
     *
     * @return string|false The parsed number or FALSE on error.
     */
    public function parse($number, array $options = []);
}
