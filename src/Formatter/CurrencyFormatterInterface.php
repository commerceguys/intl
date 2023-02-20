<?php

namespace CommerceGuys\Intl\Formatter;

interface CurrencyFormatterInterface
{
    /**
     * Formats a currency amount.
     *
     * Supported options:
     * - locale:                  The locale. Default: 'en'.
     * - use_grouping:            Whether to use grouping separators,
     *                            such as thousands separators.
     *                            Default: true.
     * - minimum_fraction_digits: Minimum fraction digits.
     * - maximum_fraction_digits: Minimum fraction digits.
     * - rounding_mode:           The rounding mode.
     *                            A PHP_ROUND_ constant or 'none' to skip
     *                            rounding. Default: PHP_ROUND_HALF_UP.
     * - style:                   The style.
     *                            One of: 'standard', 'accounting'.
     *                            Default: 'standard'.
     * - currency_display:        How the currency should be displayed.
     *                            One of: 'code', 'symbol', 'none'.
     *                            Default: 'symbol'.
     *
     * Both minimum_fraction_digits and maximum_fraction_digits default
     * to the currency's number of fraction digits.
     *
     * @param string $number       The number.
     * @param string $currencyCode The currency code.
     * @param array  $options      The formatting options.
     *
     * @return string The formatted number.
     */
    public function format(string $number, string $currencyCode, array $options = []): string;

    /**
     * Parses a formatted currency amount.
     *
     * Commonly used in input widgets where the end-user might input
     * a number using digits and symbols common to their locale.
     *
     * Supported options:
     * - locale: The locale. Default: 'en'.
     *
     * @param string $number       The formatted number.
     * @param string $currencyCode The currency code.
     * @param array  $options      The parsing options.
     *
     * @return string|false The parsed number or FALSE on error.
     */
    public function parse(string $number, string $currencyCode, array $options = []): string|bool;
}
