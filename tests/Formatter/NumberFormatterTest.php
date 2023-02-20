<?php

namespace CommerceGuys\Intl\Tests\Formatter;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\Exception\InvalidArgumentException;
use CommerceGuys\Intl\Formatter\NumberFormatter;
use CommerceGuys\Intl\NumberFormat\NumberFormat;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Formatter\NumberFormatter
 */
final class NumberFormatterTest extends TestCase
{
    /**
     * @covers ::format
     */
    public function testFormatWithInvalidOptions()
    {
        $formatter = new NumberFormatter(new NumberFormatRepository());
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
        ];

        foreach ($data as $expectedError => $options) {
            $message = '';
            try {
                $formatter->format('9.99', $options);
            } catch (InvalidArgumentException $e) {
                $message = $e->getMessage();
            }
            $this->assertEquals($expectedError, $message);
        }
    }

    /**
     * @covers ::format
     */
    public function testFormatWithInvalidNumber()
    {
        $this->expectExceptionMessage('The provided value "a12.34" is not a valid number or numeric string.');
        $this->expectException(InvalidArgumentException::class);
        $formatter = new NumberFormatter(new NumberFormatRepository());
        $formatter->format('a12.34');
    }

    /**
     * @covers ::format
     */
    public function testFormatWithFloat()
    {
        $formatter = new NumberFormatter(new NumberFormatRepository());
        $formattedNumber = $formatter->format(12.34);
        $this->assertSame('12.34', $formattedNumber);
    }

    /**
     * @covers ::format
     *
     * @dataProvider numberValueProvider
     */
    public function testBasicFormat($locale, $style, $number, $expectedNumber)
    {
        $formatter = new NumberFormatter(new NumberFormatRepository(), [
            'locale' => $locale,
            'style' => $style,
        ]);
        $formattedNumber = $formatter->format($number);
        $this->assertSame($expectedNumber, $formattedNumber);
    }

    /**
     * @covers ::format
     */
    public function testAdvancedFormat()
    {
        $formatter = new NumberFormatter(new NumberFormatRepository(), [
            'maximum_fraction_digits' => 2,
            'rounding_mode' => 'none',
        ]);

        $formattedNumber = $formatter->format('12.999');
        $this->assertSame('12.99', $formattedNumber);

        $formattedNumber = $formatter->format('12.5', [
           'minimum_fraction_digits' => 2,
        ]);
        $this->assertSame('12.50', $formattedNumber);

        $formattedNumber = $formatter->format('12', [
            'minimum_fraction_digits' => 1,
        ]);
        $this->assertSame('12.0', $formattedNumber);

        // Format with and without grouping.
        $formattedNumber = $formatter->format('10000.90');
        $this->assertSame('10,000.9', $formattedNumber);
        $formattedNumber = $formatter->format('10000.90', [
            'use_grouping' => false,
        ]);
        $this->assertSame('10000.9', $formattedNumber);

        // Test secondary groups.
        $formattedNumber = $formatter->format('12345678.90', ['locale' => 'bn']);
        $this->assertSame('১,২৩,৪৫,৬৭৮.৯', $formattedNumber);

        // No grouping needed.
        $formattedNumber = $formatter->format('123.90', ['locale' => 'bn']);
        $this->assertSame('১২৩.৯', $formattedNumber);

        // Rounding.
        $formattedNumber = $formatter->format('12.555', [
            'rounding_mode' => PHP_ROUND_HALF_UP,
        ]);
        $this->assertSame('12.56', $formattedNumber);

        $formattedNumber = $formatter->format('12.555', [
            'rounding_mode' => PHP_ROUND_HALF_DOWN,
        ]);
        $this->assertSame('12.55', $formattedNumber);
    }

    /**
     * @covers ::parse
     *
     * @dataProvider formattedValueProvider
     */
    public function testParse($locale, $number, $expectedNumber)
    {
        $formatter = new NumberFormatter(new NumberFormatRepository());
        $parsedNumber = $formatter->parse($number, ['locale' => $locale]);
        $this->assertSame($expectedNumber, $parsedNumber);
    }

    /**
     * Provides the locale, number style, value and expected formatted value.
     */
    public function numberValueProvider()
    {
        return [
            ['en', 'decimal', '-50.00', '-50'],
            ['en', 'percent', '0.50', '50%'],
            ['en', 'decimal', '5000000.5', '5,000,000.5'],
            ['bn', 'decimal', '-50.5', '-৫০.৫'],
            ['bn', 'decimal', '5000000.5', '৫০,০০,০০০.৫'],
            ['de-AT', 'decimal', '-5000.00', '-5 000'],
            ['fr-CH', 'decimal', '-5000.12', '-5 000,12'],
        ];
    }

    /**
     * Provides values for the formatted value parser.
     */
    public function formattedValueProvider()
    {
        return [
            ['en', '500,100.05', '500100.05'],
            ['en', '-1,059.59', '-1059.59'],
            ['en', '50%', '0.5'],
            ['bn', '৫,০০,১০০.০৫', '500100.05'],
        ];
    }
}
