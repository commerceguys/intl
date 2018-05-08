<?php

namespace CommerceGuys\Intl\Tests\Formatter;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\Formatter\NumberFormatter;
use CommerceGuys\Intl\NumberFormat\NumberFormat;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Formatter\NumberFormatter
 */
class NumberFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     *
     * @expectedException         \CommerceGuys\Intl\Exception\InvalidArgumentException
     * @expectedExceptionMessage  Unrecognized style "INVALID".
     */
    public function testFormatWithInvalidStyle()
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository);
        $formatter->setStyle('INVALID');
        $formatter->format('9.99');
    }

    /**
     * @covers ::format
     *
     * @expectedException \CommerceGuys\Intl\Exception\InvalidArgumentException
     * @expectedExceptionMessage The provided value "a12.34" is not a valid number or numeric string.
     */
    public function testFormatWithInvalidNumber()
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository);
        $formatter->format('a12.34');
    }

    /**
     * @covers ::format
     *
     * @dataProvider numberValueProvider
     */
    public function testBasicFormat($locale, $style, $number, $expectedNumber)
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository);
        $formatter->setStyle($style);

        $formattedNumber = $formatter->format($number, $locale);
        $this->assertSame($expectedNumber, $formattedNumber);
    }

    /**
     * @covers ::format
     */
    public function testAdvancedFormat()
    {
        $numberFormatRepository = new NumberFormatRepository();

        $formatter = new NumberFormatter($numberFormatRepository);
        $formatter->setMinimumFractionDigits(2);
        $this->assertSame('12.50', $formatter->format('12.5'));

        $formatter->setMinimumFractionDigits(1);
        $formatter->setMaximumFractionDigits(2);
        $this->assertSame('12.0', $formatter->format('12'));

        $formatter->setMinimumFractionDigits(1);
        $formatter->setMaximumFractionDigits(2);
        $this->assertSame('12.99', $formatter->format('12.999'));

        // Format with and without grouping.
        $this->assertSame('10,000.9', $formatter->format('10000.90'));
        $formatter->setGroupingUsed(false);
        $this->assertSame('10000.9', $formatter->format('10000.90'));

        // Test secondary groups.
        $formatter->setGroupingUsed(true);
        $this->assertSame('১,২৩,৪৫,৬৭৮.৯', $formatter->format('12345678.90', 'bn'));

        // No grouping needed.
        $this->assertSame('১২৩.৯', $formatter->format('123.90', 'bn'));
    }

    /**
     * @covers ::parse
     *
     * @dataProvider formattedValueProvider
     */
    public function testParse($locale, $style, $number, $expectedNumber)
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository);
        $formatter->setStyle($style);

        $parsedNumber = $formatter->parse($number, $locale);
        $this->assertSame($expectedNumber, $parsedNumber);
    }

    /**
     * @covers ::getStyle
     * @covers ::setStyle
     * @covers ::getMinimumFractionDigits
     * @covers ::setMinimumFractionDigits
     * @covers ::getMaximumFractionDigits
     * @covers ::setMaximumFractionDigits
     * @covers ::isGroupingUsed
     * @covers ::setGroupingUsed
     */
    public function testOptions()
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository);

        $this->assertEquals(NumberFormatter::STYLE_DECIMAL, $formatter->getStyle());
        $formatter->setStyle(NumberFormatter::STYLE_PERCENT);
        $this->assertEquals(NumberFormatter::STYLE_PERCENT, $formatter->getStyle());

        $this->assertEquals(0, $formatter->getMinimumFractionDigits());
        $formatter->setMinimumFractionDigits(2);
        $this->assertEquals(2, $formatter->getMinimumFractionDigits());

        $this->assertEquals(3, $formatter->getMaximumFractionDigits());
        $formatter->setMaximumFractionDigits(5);
        $this->assertEquals(5, $formatter->getMaximumFractionDigits());

        $this->assertTrue($formatter->isGroupingUsed());
        $formatter->setGroupingUsed(false);
        $this->assertFalse($formatter->isGroupingUsed());
    }

    /**
     * Provides the locale, number style, value and expected formatted value.
     */
    public function numberValueProvider()
    {
        return [
            ['en', NumberFormatter::STYLE_DECIMAL, '-50.00', '-50'],
            ['en', NumberFormatter::STYLE_PERCENT, '50.00', '50%'],
            ['en', NumberFormatter::STYLE_DECIMAL, '5000000.5', '5,000,000.5'],
            ['bn', NumberFormatter::STYLE_DECIMAL, '-50.5', '-৫০.৫'],
            ['bn', NumberFormatter::STYLE_DECIMAL, '5000000.5', '৫০,০০,০০০.৫'],
        ];
    }

    /**
     * Provides values for the formatted value parser.
     */
    public function formattedValueProvider()
    {
        return [
            ['en', NumberFormatter::STYLE_DECIMAL, '500,100.05', '500100.05'],
            ['en', NumberFormatter::STYLE_DECIMAL, '-1,059.59', '-1059.59'],
            ['bn', NumberFormatter::STYLE_DECIMAL, '৫,০০,১০০.০৫', '500100.05'],
        ];
    }
}
