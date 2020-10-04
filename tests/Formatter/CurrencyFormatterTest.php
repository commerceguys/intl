<?php

namespace CommerceGuys\Intl\Tests\Formatter;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\Currency\CurrencyRepository;
use CommerceGuys\Intl\Exception\InvalidArgumentException;
use CommerceGuys\Intl\Formatter\CurrencyFormatter;
use CommerceGuys\Intl\NumberFormat\NumberFormat;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Formatter\CurrencyFormatter
 */
final class CurrencyFormatterTest extends TestCase
{
    /**
     * @covers ::format
     */
    public function testFormatWithInvalidOptions()
    {
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository());
        $data = [
            'Unrecognized option "unknown".' => [
                'unknown' => '123',
            ],
            'The option "use_grouping" must be a boolean.' => [
                'use_grouping' => 'INVALID',
            ],
            'The option "minimum_fraction_digits" must be numeric.' => [
                'minimum_fraction_digits' => 'INVALID',
            ],
            'The option "maximum_fraction_digits" must be numeric.' => [
                'maximum_fraction_digits' => 'INVALID',
            ],
            'Unrecognized rounding mode "INVALID".' => [
                'rounding_mode' => 'INVALID',
            ],
            'Unrecognized style "INVALID".' => [
                'style' => 'INVALID',
            ],
            'Unrecognized currency display "INVALID".' => [
                'currency_display' => 'INVALID',
            ],
        ];

        foreach ($data as $expectedError => $options) {
            $message = '';
            try {
                $formatter->format('9.99', 'USD', $options);
            } catch (InvalidArgumentException $e) {
                $message = $e->getMessage();
            }
            $this->assertEquals($expectedError, $message);
        }
    }

    /**
     * @covers ::format
     *
     * @expectedException \CommerceGuys\Intl\Exception\InvalidArgumentException
     * @expectedExceptionMessage The provided value "a12.34" is not a valid number or numeric string.
     */
    public function testFormatWithInvalidNumber()
    {
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository());
        $formatter->format('a12.34', 'USD');
    }

    /**
     * @covers ::format
     */
    public function testFormatWithFloat()
    {
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository());
        $formattedNumber = $formatter->format(12.34, 'USD');
        $this->assertSame('$12.34', $formattedNumber);
    }

    /**
     * @covers ::format
     *
     * @dataProvider currencyValueProvider
     */
    public function testBasicFormat($locale, $currencyCode, $style, $number, $expectedNumber)
    {
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository(), [
            'locale' => $locale,
            'style' => $style,
            'rounding_mode' => 'none',
        ]);
        $formattedNumber = $formatter->format($number, $currencyCode);
        $this->assertSame($expectedNumber, $formattedNumber);
    }

    /**
     * @covers ::format
     */
    public function testUnknownCurrencyFormat()
    {
        // XSD doesn't exist. The formatter should use the code as-is,
        // and default to 2 fraction digits.
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository());
        $this->assertSame('XSD9.99', $formatter->format('9.99', 'XSD'));
    }

    /**
     * @covers ::format
     */
    public function testAdvancedFormat()
    {
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository(), [
            'rounding_mode' => 'none',
        ]);

        $formattedNumber = $formatter->format('12.999', 'USD');
        $this->assertSame('$12.99', $formattedNumber);

        $formattedNumber = $formatter->format('12', 'USD', [
            'minimum_fraction_digits' => 1,
        ]);
        $this->assertSame('$12.0', $formattedNumber);

        $formattedNumber = $formatter->format('12.99', 'USD', [
            'maximum_fraction_digits' => 1,
        ]);
        $this->assertSame('$12.9', $formattedNumber);

        // Format with and without grouping.
        $formattedNumber = $formatter->format('10000.90', 'USD');
        $this->assertSame('$10,000.90', $formattedNumber);
        $formattedNumber = $formatter->format('10000.90', 'USD', [
            'use_grouping' => false,
        ]);
        $this->assertSame('$10000.90', $formattedNumber);

        // Test secondary groups.
        $formattedNumber = $formatter->format('12345678.90', 'USD', ['locale' => 'bn']);
        $this->assertSame('১,২৩,৪৫,৬৭৮.৯০US$', $formattedNumber);

        // No grouping needed.
        $formattedNumber = $formatter->format('123.90', 'USD', ['locale' => 'bn']);
        $this->assertSame('১২৩.৯০US$', $formattedNumber);

        // Alternative currency display.
        $formattedNumber = $formatter->format('100', 'USD', [
            'currency_display' => 'code',
        ]);
        $this->assertSame('USD100.00', $formattedNumber);

        $formattedNumber = $formatter->format('100', 'USD', [
            'currency_display' => 'none',
        ]);
        $this->assertSame('100.00', $formattedNumber);

        // Confirm there is no trailing whitespace.
        $formattedNumber = $formatter->format('100', 'USD', [
            'locale' => 'fr',
            'currency_display' => 'none',
        ]);
        $this->assertSame('100,00', $formattedNumber);

        // Rounding.
        $formattedNumber = $formatter->format('12.555', 'USD', [
            'rounding_mode' => PHP_ROUND_HALF_UP,
        ]);
        $this->assertSame('$12.56', $formattedNumber);

        $formattedNumber = $formatter->format('12.555', 'USD', [
            'rounding_mode' => PHP_ROUND_HALF_DOWN,
        ]);
        $this->assertSame('$12.55', $formattedNumber);
    }

    /**
     * @covers ::parse
     *
     * @dataProvider formattedCurrencyProvider
     */
    public function testParse($locale, $currencyCode, $number, $expectedNumber)
    {
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository());
        $parsedNumber = $formatter->parse($number, $currencyCode, ['locale' => $locale]);
        $this->assertSame($expectedNumber, $parsedNumber);
    }

    /**
     * Provides the number format, currency format, number style, value and expected formatted value.
     */
    public function currencyValueProvider()
    {
        return [
            ['en', 'USD', 'standard', '-5.05', '-$5.05'],
            ['en', 'USD', 'accounting', '-5.05', '($5.05)'],
            ['en', 'USD', 'standard', '500100.05', '$500,100.05'],
            ['bn', 'BND', 'standard', '-50.5', '-৫০.৫০BND'],
            ['bn', 'BND', 'accounting', '-50.5', '(৫০.৫০BND)'],
            ['bn', 'BND', 'standard', '500100.05', '৫,০০,১০০.০৫BND'],
            ['de-AT', 'EUR', 'standard', '-1000.02', '-€ 1.000,02'],
            ['fr-CH', 'CHF', 'standard', '-1000.02', '-1 000.02 CHF'],
        ];
    }

    /**
     * Provides values for the formatted currency parser.
     */
    public function formattedCurrencyProvider()
    {
        return [
            ['en', 'USD', '$500,100.05', '500100.05'],
            ['en', 'USD', '-$1,059.59', '-1059.59'],
            ['en', 'USD', '($1,059.59)', '-1059.59'],
            ['bn', 'BND', '৫,০০,১০০.০৫BND', '500100.05'],
        ];
    }
}
