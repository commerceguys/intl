<?php

namespace CommerceGuys\Intl\Tests\Formatter;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\Currency\CurrencyRepository;
use CommerceGuys\Intl\Formatter\CurrencyFormatter;
use CommerceGuys\Intl\NumberFormat\NumberFormat;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Formatter\CurrencyFormatter
 */
class CurrencyFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     *
     * @expectedException        \CommerceGuys\Intl\Exception\InvalidArgumentException
     * @expectedExceptionMessage Unrecognized style "INVALID".
     */
    public function testFormatWithInvalidStyle()
    {
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository());
        $formatter->setStyle('INVALID');
        $formatter->format('9.99', 'USD');
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
     *
     * @dataProvider currencyValueProvider
     */
    public function testBasicFormat($locale, $currencyCode, $style, $number, $expectedNumber)
    {
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository());
        $formatter->setStyle($style);
        $formatter->setRoundingMode(CurrencyFormatter::ROUND_NONE);

        $formattedNumber = $formatter->format($number, $currencyCode, $locale);
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
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository());
        $formatter->setRoundingMode(CurrencyFormatter::ROUND_NONE);

        $formatter->setMinimumFractionDigits(2);
        $this->assertSame('$12.50', $formatter->format('12.5', 'USD'));

        $formatter->setMinimumFractionDigits(1);
        $formatter->setMaximumFractionDigits(2);
        $this->assertSame('$12.0', $formatter->format('12', 'USD'));

        $formatter->setMinimumFractionDigits(1);
        $formatter->setMaximumFractionDigits(2);
        $this->assertSame('$12.99', $formatter->format('12.999', 'USD'));

        // Format with and without grouping.
        $this->assertSame('$10,000.9', $formatter->format('10000.90', 'USD'));
        $formatter->setGroupingUsed(false);
        $this->assertSame('$10000.9', $formatter->format('10000.90', 'USD'));

        // Test secondary groups.
        $formatter->setGroupingUsed(true);
        $this->assertSame('১,২৩,৪৫,৬৭৮.৯US$', $formatter->format('12345678.90', 'USD', 'bn'));

        // No grouping needed.
        $this->assertSame('১২৩.৯US$', $formatter->format('123.90', 'USD', 'bn'));

        // Alternative currency display.
        $formatter->setCurrencyDisplay(CurrencyFormatter::CURRENCY_DISPLAY_CODE);
        $this->assertSame('USD100.0', $formatter->format('100', 'USD'));

        $formatter->setCurrencyDisplay(CurrencyFormatter::CURRENCY_DISPLAY_NONE);
        $this->assertSame('100.0', $formatter->format('100', 'USD'));

        // Rounding.
        $formatter->setRoundingMode(CurrencyFormatter::ROUND_HALF_UP);
        $this->assertSame('12.56', $formatter->format('12.555', 'USD'));

        $formatter->setRoundingMode(CurrencyFormatter::ROUND_HALF_DOWN);
        $this->assertSame('12.55', $formatter->format('12.555', 'USD'));
    }

    /**
     * @covers ::parse
     *
     * @dataProvider formattedCurrencyProvider
     */
    public function testParse($locale, $currencyCode, $style, $number, $expectedNumber)
    {
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository());
        $formatter->setStyle($style);

        $parsedNumber = $formatter->parse($number, $currencyCode, $locale);
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
     * @covers ::getCurrencyDisplay
     * @covers ::setCurrencyDisplay
     */
    public function testOptions()
    {
        $formatter = new CurrencyFormatter(new NumberFormatRepository(), new CurrencyRepository());

        $this->assertEquals(CurrencyFormatter::STYLE_STANDARD, $formatter->getStyle());
        $formatter->setStyle(CurrencyFormatter::STYLE_ACCOUNTING);
        $this->assertEquals(CurrencyFormatter::STYLE_ACCOUNTING, $formatter->getStyle());

        $this->assertEquals(null, $formatter->getMinimumFractionDigits());
        $formatter->setMinimumFractionDigits(2);
        $this->assertEquals(2, $formatter->getMinimumFractionDigits());

        $this->assertEquals(null, $formatter->getMaximumFractionDigits());
        $formatter->setMaximumFractionDigits(5);
        $this->assertEquals(5, $formatter->getMaximumFractionDigits());

        $this->assertTrue($formatter->isGroupingUsed());
        $formatter->setGroupingUsed(false);
        $this->assertFalse($formatter->isGroupingUsed());

        $this->assertEquals(CurrencyFormatter::CURRENCY_DISPLAY_SYMBOL, $formatter->getCurrencyDisplay());
        $formatter->setCurrencyDisplay(CurrencyFormatter::CURRENCY_DISPLAY_CODE);
        $this->assertEquals(CurrencyFormatter::CURRENCY_DISPLAY_CODE, $formatter->getCurrencyDisplay());
    }

    /**
     * Provides the number format, currency format, number style, value and expected formatted value.
     */
    public function currencyValueProvider()
    {
        return [
            ['en', 'USD', CurrencyFormatter::STYLE_STANDARD, '-5.05', '-$5.05'],
            ['en', 'USD', CurrencyFormatter::STYLE_ACCOUNTING, '-5.05', '($5.05)'],
            ['en', 'USD', CurrencyFormatter::STYLE_STANDARD, '500100.05', '$500,100.05'],
            ['bn', 'BND', CurrencyFormatter::STYLE_STANDARD, '-50.5', '-৫০.৫০BND'],
            ['bn', 'BND', CurrencyFormatter::STYLE_ACCOUNTING, '-50.5', '(৫০.৫০BND)'],
            ['bn', 'BND', CurrencyFormatter::STYLE_STANDARD, '500100.05', '৫,০০,১০০.০৫BND'],
        ];
    }

    /**
     * Provides values for the formatted currency parser.
     */
    public function formattedCurrencyProvider()
    {
        return [
            ['en', 'USD', CurrencyFormatter::STYLE_STANDARD, '$500,100.05', '500100.05'],
            ['en', 'USD', CurrencyFormatter::STYLE_STANDARD, '-$1,059.59', '-1059.59'],
            ['en', 'USD', CurrencyFormatter::STYLE_ACCOUNTING, '($1,059.59)', '-1059.59'],
            ['bn', 'BND', CurrencyFormatter::STYLE_STANDARD, '৫,০০,১০০.০৫BND', '500100.05'],
        ];
    }
}
