<?php

namespace CommerceGuys\Intl\Tests\Formatter;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\Formatter\CurrencyFormatter;
use CommerceGuys\Intl\NumberFormat\NumberFormat;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Formatter\CurrencyFormatter
 */
class CurrencyFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepare two currencies.
     */
    protected $currencies = [
        'USD' => [
            'currency_code' => 'USD',
            'name' => 'US Dollar',
            'numeric_code' => '840',
            'symbol' => '$',
            'locale' => 'en',
        ],
        'BND' => [
            'currency_code' => 'BND',
            'name' => 'dollar Brunei',
            'numeric_code' => '096',
            'symbol' => 'BND',
            'locale' => 'bn',
        ],
    ];

    /**
     * @covers ::__construct
     *
     * @expectedException        \CommerceGuys\Intl\Exception\InvalidArgumentException
     * @expectedExceptionMessage Unrecognized style "INVALID".
     */
    public function testFormatWithInvalidStyle()
    {
        $currency = new Currency($this->currencies['USD']);
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new CurrencyFormatter($numberFormatRepository);
        $formatter->setStyle('INVALID');
        $formatter->format('9.99', $currency);
    }

    /**
     * @covers ::format
     *
     * @expectedException \CommerceGuys\Intl\Exception\InvalidArgumentException
     * @expectedExceptionMessage The provided value "a12.34" is not a valid number or numeric string.
     */
    public function testFormatWithInvalidNumber()
    {
        $currency = new Currency($this->currencies['USD']);
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new CurrencyFormatter($numberFormatRepository);
        $formatter->format('a12.34', $currency);
    }

    /**
     * @covers ::format
     *
     * @dataProvider currencyValueProvider
     */
    public function testBasicFormat($locale, $currency, $style, $number, $expectedNumber)
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new CurrencyFormatter($numberFormatRepository);
        $formatter->setStyle($style);

        $formattedNumber = $formatter->format($number, $currency, $locale);
        $this->assertSame($expectedNumber, $formattedNumber);
    }

    /**
     * @covers ::format
     */
    public function testAdvancedFormat()
    {
        $currency = new Currency($this->currencies['USD']);
        $numberFormatRepository = new NumberFormatRepository();

        $formatter = new CurrencyFormatter($numberFormatRepository);
        $formatter->setMinimumFractionDigits(2);
        $this->assertSame('$12.50', $formatter->format('12.5', $currency));

        $formatter->setMinimumFractionDigits(1);
        $formatter->setMaximumFractionDigits(2);
        $this->assertSame('$12.0', $formatter->format('12', $currency));

        $formatter->setMinimumFractionDigits(1);
        $formatter->setMaximumFractionDigits(2);
        $this->assertSame('$12.99', $formatter->format('12.999', $currency));

        // Format with and without grouping.
        $this->assertSame('$10,000.9', $formatter->format('10000.90', $currency));
        $formatter->setGroupingUsed(false);
        $this->assertSame('$10000.9', $formatter->format('10000.90', $currency));

        // Test secondary groups.
        $formatter->setGroupingUsed(true);
        $this->assertSame('১,২৩,৪৫,৬৭৮.৯$', $formatter->format('12345678.90', $currency, 'bn'));

        // No grouping needed.
        $this->assertSame('১২৩.৯$', $formatter->format('123.90', $currency, 'bn'));

        // Alternative currency display.
        $formatter->setCurrencyDisplay(CurrencyFormatter::CURRENCY_DISPLAY_CODE);
        $this->assertSame('USD100.0', $formatter->format('100', $currency));

        $formatter->setCurrencyDisplay(CurrencyFormatter::CURRENCY_DISPLAY_NONE);
        $this->assertSame('100.0', $formatter->format('100', $currency));
    }

    /**
     * @covers ::parse
     *
     * @dataProvider formattedCurrencyProvider
     */
    public function testParse($locale, $currency, $style, $number, $expectedNumber)
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new CurrencyFormatter($numberFormatRepository);
        $formatter->setStyle($style);

        $parsedNumber = $formatter->parse($number, $currency, $locale);
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
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new CurrencyFormatter($numberFormatRepository);

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
            ['en', new Currency($this->currencies['USD']), CurrencyFormatter::STYLE_STANDARD, '-5.05', '-$5.05'],
            ['en', new Currency($this->currencies['USD']), CurrencyFormatter::STYLE_ACCOUNTING, '-5.05', '($5.05)'],
            ['en', new Currency($this->currencies['USD']), CurrencyFormatter::STYLE_STANDARD, '500100.05', '$500,100.05'],
            ['bn', new Currency($this->currencies['BND']), CurrencyFormatter::STYLE_STANDARD, '-50.5', '-৫০.৫০BND'],
            ['bn', new Currency($this->currencies['BND']), CurrencyFormatter::STYLE_ACCOUNTING, '-50.5', '(৫০.৫০BND)'],
            ['bn', new Currency($this->currencies['BND']), CurrencyFormatter::STYLE_STANDARD, '500100.05', '৫,০০,১০০.০৫BND'],
        ];
    }

    /**
     * Provides values for the formatted currency parser.
     */
    public function formattedCurrencyProvider()
    {
        return [
            ['en', new Currency($this->currencies['USD']), CurrencyFormatter::STYLE_STANDARD, '$500,100.05', '500100.05'],
            ['en', new Currency($this->currencies['USD']), CurrencyFormatter::STYLE_STANDARD, '-$1,059.59', '-1059.59'],
            ['en', new Currency($this->currencies['USD']), CurrencyFormatter::STYLE_ACCOUNTING, '($1,059.59)', '-1059.59'],
            ['bn', new Currency($this->currencies['BND']), CurrencyFormatter::STYLE_STANDARD, '৫,০০,১০০.০৫BND', '500100.05'],
        ];
    }
}
