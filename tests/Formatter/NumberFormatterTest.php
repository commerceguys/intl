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
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     *
     * @expectedException         \CommerceGuys\Intl\Exception\InvalidArgumentException
     * @expectedExceptionMessage  Unknown format style provided to NumberFormatter::__construct().
     */
    public function testConstructorWithInvalidStyle()
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository, 'foo');
    }

    /**
     * @covers ::format
     * @covers ::replaceDigits
     * @covers ::replaceSymbols
     *
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     *
     * @dataProvider numberValueProvider
     */
    public function testFormat($locale, $style, $number, $expectedNumber)
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository, $style);

        $formattedNumber = $formatter->format($number, $locale);
        $this->assertSame($expectedNumber, $formattedNumber);
    }

    /**
     * @covers ::SetMinimumFractionDigits
     * @covers ::SetMaximumFractionDigits
     * @covers ::format
     *
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::replaceDigits
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::replaceSymbols
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     */
    public function testFormatFractionDigits()
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository);
        $formatter->setMinimumFractionDigits(2);
        $formattedNumber = $formatter->format('12.5');
        $this->assertSame('12.50', $formattedNumber);

        $formatter->setMaximumFractionDigits(1);
        $formattedNumber = $formatter->format('12.50');
        $this->assertSame('12.5', $formattedNumber);

        $formatter->setMinimumFractionDigits(4);
        $formatter->setMaximumFractionDigits(5);
        $formattedNumber = $formatter->format('12.50000');
        $this->assertSame('12.5000', $formattedNumber);

        $formatter->setMinimumFractionDigits(1);
        $formatter->setMaximumFractionDigits(2);
        $formattedNumber = $formatter->format('12.0000');
        $this->assertSame('12.0', $formattedNumber);

        $formatter->setMinimumFractionDigits(1);
        $formatter->setMaximumFractionDigits(2);
        $formattedNumber = $formatter->format('12');
        $this->assertSame('12.0', $formattedNumber);
    }

    /**
     * @covers ::format
     *
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::format
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     *
     * @expectedException \CommerceGuys\Intl\Exception\InvalidArgumentException
     */
    public function testFormatOnlyAllowsNumbers()
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository);
        $formatter->format('a12.34');
    }

    /**
     * @covers ::formatCurrency
     * @covers ::replaceSymbols
     *
     * @uses \CommerceGuys\Intl\Currency\Currency
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::format
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::replaceDigits
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     *
     * @dataProvider currencyValueProvider
     */
    public function testFormatCurrency($locale, $currency, $style, $number, $expectedNumber)
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository, $style);

        $formattedNumber = $formatter->formatCurrency($number, $currency, $locale);
        $this->assertSame($expectedNumber, $formattedNumber);
    }

    /**
     * @covers ::parse
     *
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     *
     * @dataProvider formattedValueProvider
     */
    public function testParse($locale, $style, $number, $expectedNumber)
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository, $style);

        $parsedNumber = $formatter->parse($number, $locale);
        $this->assertSame($expectedNumber, $parsedNumber);
    }

    /**
     * @covers ::parseCurrency
     *
     * @uses \CommerceGuys\Intl\Currency\Currency
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     *
     * @dataProvider formattedCurrencyProvider
     */
    public function testParseCurrency($locale, $currency, $style, $number, $expectedNumber)
    {
        $numberFormatRepository = new NumberFormatRepository();
        $formatter = new NumberFormatter($numberFormatRepository, $style);

        $parsedNumber = $formatter->parseCurrency($number, $currency, $locale);
        $this->assertSame($expectedNumber, $parsedNumber);
    }

    /**
     * @covers ::getMinimumFractionDigits
     *
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     */
    public function testMinimumFractionDigits()
    {
        $numberFormatRepository = new NumberFormatRepository();
        // Defaults to 0 for decimal and percentage formats.
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::DECIMAL);
        $this->assertEquals(0, $formatter->getMinimumFractionDigits());
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::PERCENT);
        $this->assertEquals(0, $formatter->getMinimumFractionDigits());

        // Should default to null for currency formats.
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::CURRENCY);
        $this->assertNull($formatter->getMinimumFractionDigits());
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::CURRENCY_ACCOUNTING);
        $this->assertNull($formatter->getMinimumFractionDigits());
    }

    /**
     * @covers ::getMaximumFractionDigits
     *
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     */
    public function testMaximumFractionDigits()
    {
        $numberFormatRepository = new NumberFormatRepository();
        // Defaults to 3 for decimal and percentage formats.
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::DECIMAL);
        $this->assertEquals(3, $formatter->getMaximumFractionDigits());
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::PERCENT);
        $this->assertEquals(3, $formatter->getMaximumFractionDigits());

        // Should default to null for currency formats.
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::CURRENCY);
        $this->assertNull($formatter->getMaximumFractionDigits());
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::CURRENCY_ACCOUNTING);
        $this->assertNull($formatter->getMaximumFractionDigits());
    }

    /**
     * @covers ::isGroupingUsed
     * @covers ::setGroupingUsed
     *
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::format
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::replaceDigits
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::replaceSymbols
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     */
    public function testGroupingUsed()
    {
        $numberFormatRepository = new NumberFormatRepository();
        // The formatter groups correctly.
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::DECIMAL);
        $this->assertSame('10,000.9', $formatter->format('10000.90'));

        // The formatter respects grouping turned off.
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::DECIMAL);
        $formatter->setGroupingUsed(false);
        $this->assertFalse($formatter->isGroupingUsed());
        $this->assertSame('10000.9', $formatter->format('10000.90'));

        // Test secondary groups.
        $formatter->setGroupingUsed(true);
        $this->assertSame('১,২৩,৪৫,৬৭৮.৯', $formatter->format('12345678.90', 'bn'));

        // No grouping needed.
        $this->assertSame('১২৩.৯', $formatter->format('123.90', 'bn'));
    }

    /**
     * @covers ::getCurrencyDisplay
     * @covers ::setCurrencyDisplay
     * @covers ::formatCurrency
     *
     * @uses \CommerceGuys\Intl\Currency\Currency
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::format
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::replaceDigits
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::replaceSymbols
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     */
    public function testCurrencyDisplay()
    {
        $numberFormatRepository = new NumberFormatRepository();
        $currency = new Currency($this->currencies['USD']);

        // Currency display defaults to symbol.
        $formatter = new NumberFormatter($numberFormatRepository, NumberFormatter::CURRENCY);
        $this->assertSame(NumberFormatter::CURRENCY_DISPLAY_SYMBOL, $formatter->getCurrencyDisplay());
        $formattedNumber = $formatter->formatCurrency('100', $currency);
        $this->assertSame('$100.00', $formattedNumber);

        $formatter->setCurrencyDisplay(NumberFormatter::CURRENCY_DISPLAY_CODE);
        $this->assertSame(NumberFormatter::CURRENCY_DISPLAY_CODE, $formatter->getCurrencyDisplay());
        $formattedNumber = $formatter->formatCurrency('100', $currency);
        $this->assertSame('USD100.00', $formattedNumber);

        $formatter->setCurrencyDisplay(NumberFormatter::CURRENCY_DISPLAY_NONE);
        $this->assertSame(NumberFormatter::CURRENCY_DISPLAY_NONE, $formatter->getCurrencyDisplay());
        $formattedNumber = $formatter->formatCurrency('100', $currency);
        $this->assertSame('100.00', $formattedNumber);
    }

    /**
     * Provides the locale, number style, value and expected formatted value.
     */
    public function numberValueProvider()
    {
        return [
            ['en', NumberFormatter::DECIMAL, '-50.5', '-50.5'],
            ['en', NumberFormatter::PERCENT, '50.5', '50.5%'],
            ['en', NumberFormatter::DECIMAL, '5000000.5', '5,000,000.5'],
            ['bn', NumberFormatter::DECIMAL, '-50.5', '-৫০.৫'],
            ['bn', NumberFormatter::DECIMAL, '5000000.5', '৫০,০০,০০০.৫'],
        ];
    }

    /**
     * Provides the number format, currency format, number style, value and expected formatted value.
     */
    public function currencyValueProvider()
    {
        return [
            ['en', new Currency($this->currencies['USD']), NumberFormatter::CURRENCY, '-5.05', '-$5.05'],
            ['en', new Currency($this->currencies['USD']), NumberFormatter::CURRENCY_ACCOUNTING, '-5.05', '($5.05)'],
            ['en', new Currency($this->currencies['USD']), NumberFormatter::CURRENCY, '500100.05', '$500,100.05'],
            ['bn', new Currency($this->currencies['BND']), NumberFormatter::CURRENCY, '-50.5', '-৫০.৫০BND'],
            ['bn', new Currency($this->currencies['BND']), NumberFormatter::CURRENCY_ACCOUNTING, '-50.5', '(৫০.৫০BND)'],
            ['bn', new Currency($this->currencies['BND']), NumberFormatter::CURRENCY, '500100.05', '৫,০০,১০০.০৫BND'],
        ];
    }

    /**
     * Provides values for the formatted value parser.
     */
    public function formattedValueProvider()
    {
        return [
            ['en', NumberFormatter::DECIMAL, '500,100.05', '500100.05'],
            ['en', NumberFormatter::DECIMAL, '-1,059.59', '-1059.59'],
            ['bn', NumberFormatter::DECIMAL, '৫,০০,১০০.০৫', '500100.05'],
        ];
    }

    /**
     * Provides values for the formatted currency parser.
     */
    public function formattedCurrencyProvider()
    {
        return [
            ['en', new Currency($this->currencies['USD']), NumberFormatter::CURRENCY, '$500,100.05', '500100.05'],
            ['en', new Currency($this->currencies['USD']), NumberFormatter::CURRENCY, '-$1,059.59', '-1059.59'],
            ['en', new Currency($this->currencies['USD']), NumberFormatter::CURRENCY_ACCOUNTING, '($1,059.59)', '-1059.59'],
            ['bn', new Currency($this->currencies['BND']), NumberFormatter::CURRENCY, '৫,০০,১০০.০৫BND', '500100.05'],
        ];
    }
}
