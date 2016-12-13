<?php

namespace CommerceGuys\Intl;

/**
 * Provides helpers for bcmath-based arithmetic.
 *
 * The bcmath extension provides support for arbitrary precision arithmetic,
 * which does not suffer from the precision loses that make floating point
 * arithmetic unsafe for eCommerce.
 *
 * Important: All numbers must be passed as strings.
 */
final class Calculator
{
    /**
     * Adds the second number to the first number.
     *
     * @param string $first_number  The first number.
     * @param string $second_number The second number.
     * @param int $scale            The maximum number of digits after the
     *                              decimal place. Any digit after $scale will
     *                              be truncated.
     *
     * @return string The result.
     */
    public static function add($first_number, $second_number, $scale = 6)
    {
        self::assertNumberFormat($first_number);
        self::assertNumberFormat($second_number);
        $result = bcadd($first_number, $second_number, $scale);

        return self::trim($result);
    }

    /**
     * Subtracts the second number from the first number.
     *
     * @param string $first_number  The first number.
     * @param string $second_number The second number.
     * @param int $scale            The maximum number of digits after the
     *                              decimal place. Any digit after $scale will
     *                              be truncated.
     *
     * @return string The result.
     */
    public static function subtract($first_number, $second_number, $scale = 6)
    {
        self::assertNumberFormat($first_number);
        self::assertNumberFormat($second_number);
        $result = bcsub($first_number, $second_number, $scale);

        return self::trim($result);
    }

    /**
     * Multiplies the first number by the second number.
     *
     * @param string $first_number  The first number.
     * @param string $second_number The second number.
     * @param int $scale            The maximum number of digits after the
     *                              decimal place. Any digit after $scale will
     *                              be truncated.
     *
     * @return string The result.
     */
    public static function multiply($first_number, $second_number, $scale = 6)
    {
        self::assertNumberFormat($first_number);
        self::assertNumberFormat($second_number);
        $result = bcmul($first_number, $second_number, $scale);

        return self::trim($result);
    }

    /**
     * Divides the first number by the second number.
     *
     * @param string $first_number  The first number.
     * @param string $second_number The second number.
     * @param int $scale            The maximum number of digits after the
     *                              decimal place. Any digit after $scale will
     *                              be truncated.
     *
     * @return string The result.
     */
    public static function divide($first_number, $second_number, $scale = 6)
    {
        self::assertNumberFormat($first_number);
        self::assertNumberFormat($second_number);
        $result = bcdiv($first_number, $second_number, $scale);

        return self::trim($result);
    }

    /**
     * Calculates the next highest whole value of a number.
     *
     * @param string $number The number.
     *
     * @return string The result.
     */
    public static function ceil($number)
    {
        if (self::compare($number, 0) == 1) {
            $result = bcadd($number, '1', 0);
        } else {
            $result = bcadd($number, '0', 0);
        }

        return $result;
    }

    /**
     * Calculates the next lowest whole value of a number.
     *
     * @param string $number The number.
     *
     * @return string The result.
     */
    public static function floor($number)
    {
        if (self::compare($number, 0) == 1) {
            $result = bcadd($number, '0', 0);
        } else {
            $result = bcadd($number, '-1', 0);
        }

        return $result;
    }

    /**
     * Rounds the given number.
     *
     * Replicates PHP's support for rounding to the nearest even/odd number
     * even if that number is decimal ($precision > 0).
     *
     * @param string $number The number.
     * @param int $precision The number of decimals to round to.
     * @param int $mode      The rounding mode. One of the following constants:
     *                       PHP_ROUND_HALF_UP, PHP_ROUND_HALF_DOWN,
     *                       PHP_ROUND_HALF_EVEN, PHP_ROUND_HALF_ODD.
     *
     * @return string The rounded number.
     *
     * @throws \InvalidArgumentException
     */
    public static function round($number, $precision = 0, $mode = PHP_ROUND_HALF_UP)
    {
        self::assertNumberFormat($number);
        if (!is_numeric($precision) || $precision < 0) {
            throw new \InvalidArgumentException('The provided precision should be a positive number');
        }

        // Round the number in both directions (up/down) before choosing one.
        $rounding_increment = bcdiv('1', pow(10, $precision), $precision);
        if (self::compare($number, '0') == 1) {
            $rounded_up = bcadd($number, $rounding_increment, $precision);
        } else {
            $rounded_up = bcsub($number, $rounding_increment, $precision);
        }
        $rounded_down = bcsub($number, 0, $precision);
        // The rounding direction is based on the first decimal after $precision.
        $number_parts = explode('.', $number);
        $decimals = !empty($number_parts[1]) ? $number_parts[1] : '0';
        $relevant_decimal = isset($decimals[$precision]) ? $decimals[$precision] : 0;
        if ($relevant_decimal < 5) {
            $number = $rounded_down;
        } elseif ($relevant_decimal == 5) {
            if ($mode == PHP_ROUND_HALF_UP) {
                $number = $rounded_up;
            } elseif ($mode == PHP_ROUND_HALF_DOWN) {
                $number = $rounded_down;
            } elseif ($mode == PHP_ROUND_HALF_EVEN) {
                $integer = bcmul($rounded_up, pow(10, $precision), 0);
                $number = bcmod($integer, '2') == 0 ? $rounded_up : $rounded_down;
            } elseif ($mode == PHP_ROUND_HALF_ODD) {
                $integer = bcmul($rounded_up, pow(10, $precision), 0);
                $number = bcmod($integer, '2') != 0 ? $rounded_up : $rounded_down;
            }
        } elseif ($relevant_decimal > 5) {
            $number = $rounded_up;
        }

        return $number;
    }

    /**
     * Compares the first number to the second number.
     *
     * @param string $first_number  The first number.
     * @param string $second_number The second number.
     * @param int $scale            The maximum number of digits after the
     *                              decimal place. Any digit after $scale will
     *                              be truncated.
     *
     * @return int 0 if both numbers are equal, 1 if the first one is greater,
     *             -1 otherwise.
     */
    public static function compare($first_number, $second_number, $scale = 6)
    {
        self::assertNumberFormat($first_number);
        self::assertNumberFormat($second_number);

        return bccomp($first_number, $second_number, $scale);
    }

    /**
     * Trims the given number.
     *
     * By default bcmath returns numbers with the number of digits according
     * to $scale. This means that bcadd('2', '2', 6) will return '4.00000'.
     * Trimming the number removes the excess zeroes.
     *
     * @param string $number The number to trim.
     *
     * @return string The trimmed number.
     */
    public static function trim($number)
    {
        if (strpos($number, '.') != false) {
            // The number is decimal, strip trailing zeroes.
            // If no digits remain after the decimal point, strip it as well.
            $number = rtrim($number, '0');
            $number = rtrim($number, '.');
        }

        return $number;
    }

    /**
     * Assert that the given number is a numeric string value.
     *
     * @param string $number The number to check.
     *
     * @throws \InvalidArgumentException
     */
    public static function assertNumberFormat($number)
    {
        if (is_float($number)) {
            throw new \InvalidArgumentException(sprintf('The provided value "%s" must be a string, not a float.', $number));
        }
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException(sprintf('The provided value "%s" is not a numeric value.', $number));
        }
    }
}
