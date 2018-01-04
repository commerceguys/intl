<?php

namespace CommerceGuys\Intl\Tests\Formatter;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\Formatter\NumberFormatter;
use CommerceGuys\Intl\NumberFormat\NumberFormat;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Formatter\NumberFormatter
 */
class NumberFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepare two number formats.
     */
    protected $numberFormats = [
        'en' => [
            'locale' => 'en',
            'numbering_system' => 'latn',
            'decimal_pattern' => '#,##0.###',
            'percent_pattern' => '#,##0%',
            'currency_pattern' => '¤#,##0.00',
            'accounting_currency_pattern' => '¤#,##0.00;(¤#,##0.00)',
        ],
        'bn' => [
            'locale' => 'bn',
            'numbering_system' => 'beng',
            'decimal_pattern' => '#,##,##0.###',
            'percent_pattern' => '#,##,##0%',
            'currency_pattern' => '#,##,##0.00¤',
            'accounting_currency_pattern' => '#,##,##0.00¤;(#,##,##0.00¤)',
        ],
    ];

    /**
     * Prepare two currency formats.
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
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::getNumberFormat
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     */
    public function testConstructor()
    {
        $numberFormat = new NumberFormat($this->numberFormats['en']);
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::DECIMAL);
        $this->assertSame($numberFormat, $formatter->getNumberFormat());
    }

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
        $numberFormat = new NumberFormat($this->numberFormats['en']);
        new NumberFormatter($numberFormat, 'foo');
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
    public function testFormat($number_format, $style, $value, $expected_value)
    {
        $formatter = new NumberFormatter($number_format, $style);

        $formattedNumber = $formatter->format($value);
        $this->assertSame($expected_value, $formattedNumber);
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
        $numberFormat = new NumberFormat($this->numberFormats['en']);

        $formatter = new NumberFormatter($numberFormat);
        $formatter->setMinimumFractionDigits(2);
        $formattedNumber = $formatter->format('12.5');
        $this->assertSame('12.50', $formattedNumber);

        $formatter = new NumberFormatter($numberFormat);
        $formatter->setMaximumFractionDigits(1);
        $formattedNumber = $formatter->format('12.50');
        $this->assertSame('12.5', $formattedNumber);

        $formatter = new NumberFormatter($numberFormat);
        $formatter->setMinimumFractionDigits(4);
        $formatter->setMaximumFractionDigits(5);
        $formattedNumber = $formatter->format('12.50000');
        $this->assertSame('12.5000', $formattedNumber);

        $formatter = new NumberFormatter($numberFormat);
        $formatter->setMinimumFractionDigits(1);
        $formatter->setMaximumFractionDigits(2);
        $formattedNumber = $formatter->format('12.0000');
        $this->assertSame('12.0', $formattedNumber);

        $formatter = new NumberFormatter($numberFormat);
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
        $numberFormat = new NumberFormat($this->numberFormats['en']);
        $formatter = new NumberFormatter($numberFormat);
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
    public function testFormatCurrency($number_format, $currency, $style, $value, $expected_value)
    {
        $formatter = new NumberFormatter($number_format, $style);

        $formattedNumber = $formatter->formatCurrency($value, $currency);
        $this->assertSame($expected_value, $formattedNumber);
    }

    /**
     * @covers ::parse
     *
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     *
     * @dataProvider formattedValueProvider
     */
    public function testParse($number_format, $style, $value, $expected_value)
    {
        $formatter = new NumberFormatter($number_format, $style);

        $parsedNumber = $formatter->parse($value);
        $this->assertSame($expected_value, $parsedNumber);
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
    public function testParseCurrency($number_format, $currency, $style, $value, $expected_value)
    {
        $formatter = new NumberFormatter($number_format, $style);

        $parsedNumber = $formatter->parseCurrency($value, $currency);
        $this->assertSame($expected_value, $parsedNumber);
    }

    /**
     * @covers ::getNumberFormat
     *
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     */
    public function testGetNumberFormat()
    {
        $numberFormat = new NumberFormat($this->numberFormats['en']);
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::DECIMAL);
        $this->assertSame($numberFormat, $formatter->getNumberFormat());
    }

    /**
     * @covers ::getMinimumFractionDigits
     *
     * @uses \CommerceGuys\Intl\Formatter\NumberFormatter::__construct
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     */
    public function testMinimumFractionDigits()
    {
        $numberFormat = new NumberFormat($this->numberFormats['en']);

        // Defaults to 0 for decimal and percentage formats.
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::DECIMAL);
        $this->assertEquals(0, $formatter->getMinimumFractionDigits());
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::PERCENT);
        $this->assertEquals(0, $formatter->getMinimumFractionDigits());

        // Should default to null for currency formats.
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::CURRENCY);
        $this->assertNull($formatter->getMinimumFractionDigits());
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::CURRENCY_ACCOUNTING);
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
        $numberFormat = new NumberFormat($this->numberFormats['en']);

        // Defaults to 3 for decimal and percentage formats.
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::DECIMAL);
        $this->assertEquals(3, $formatter->getMaximumFractionDigits());
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::PERCENT);
        $this->assertEquals(3, $formatter->getMaximumFractionDigits());

        // Should default to null for currency formats.
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::CURRENCY);
        $this->assertNull($formatter->getMaximumFractionDigits());
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::CURRENCY_ACCOUNTING);
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
        $numberFormat = new NumberFormat($this->numberFormats['en']);

        // The formatter groups correctly.
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::DECIMAL);
        $this->assertTrue($formatter->isGroupingUsed());
        $this->assertSame('10,000.9', $formatter->format('10000.90'));

        // The formatter respects grouping turned off.
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::DECIMAL);
        $formatter->setGroupingUsed(false);
        $this->assertFalse($formatter->isGroupingUsed());
        $this->assertSame('10000.9', $formatter->format('10000.90'));
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
        $numberFormat = new NumberFormat($this->numberFormats['en']);
        $currency = new Currency($this->currencies['USD']);

        // Currency display defaults to symbol.
        $formatter = new NumberFormatter($numberFormat, NumberFormatter::CURRENCY);
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
     * Provides the number format, number style, value and expected formatted value.
     */
    public function numberValueProvider()
    {
        return [
            [new NumberFormat($this->numberFormats['en']), NumberFormatter::DECIMAL, '-50.5', '-50.5'],
            [new NumberFormat($this->numberFormats['en']), NumberFormatter::PERCENT, '50.5', '50.5%'],
            [new NumberFormat($this->numberFormats['en']), NumberFormatter::DECIMAL, '5000000.5', '5,000,000.5'],
            [new NumberFormat($this->numberFormats['bn']), NumberFormatter::DECIMAL, '-50.5', '-৫০.৫'],
            [new NumberFormat($this->numberFormats['bn']), NumberFormatter::PERCENT, '50.5', '৫০.৫%'],
            [new NumberFormat($this->numberFormats['bn']), NumberFormatter::DECIMAL, '5000000.5', '৫০,০০,০০০.৫'],
        ];
    }

    /**
     * Provides the number format, currency format, number style, value and expected formatted value.
     */
    public function currencyValueProvider()
    {
        return [
            [new NumberFormat($this->numberFormats['en']), new Currency($this->currencies['USD']), NumberFormatter::CURRENCY, '-5.05', '-$5.05'],
            [new NumberFormat($this->numberFormats['en']), new Currency($this->currencies['USD']), NumberFormatter::CURRENCY_ACCOUNTING, '-5.05', '($5.05)'],
            [new NumberFormat($this->numberFormats['en']), new Currency($this->currencies['USD']), NumberFormatter::CURRENCY, '500100.05', '$500,100.05'],
            [new NumberFormat($this->numberFormats['bn']), new Currency($this->currencies['BND']), NumberFormatter::CURRENCY, '-50.5', '-৫০.৫০BND'],
            [new NumberFormat($this->numberFormats['bn']), new Currency($this->currencies['BND']), NumberFormatter::CURRENCY_ACCOUNTING, '-50.5', '(৫০.৫০BND)'],
            [new NumberFormat($this->numberFormats['bn']), new Currency($this->currencies['BND']), NumberFormatter::CURRENCY, '500100.05', '৫,০০,১০০.০৫BND'],
        ];
    }

    /**
     * Provides values for the formatted value parser.
     */
    public function formattedValueProvider()
    {
        return [
            [new NumberFormat($this->numberFormats['en']), NumberFormatter::DECIMAL, '500,100.05', '500100.05'],
            [new NumberFormat($this->numberFormats['en']), NumberFormatter::DECIMAL, '-1,059.59', '-1059.59'],
            [new NumberFormat($this->numberFormats['bn']), NumberFormatter::DECIMAL, '৫,০০,১০০.০৫', '500100.05'],
        ];
    }

    /**
     * Provides values for the formatted currency parser.
     */
    public function formattedCurrencyProvider()
    {
        return [
            [new NumberFormat($this->numberFormats['en']), new Currency($this->currencies['USD']), NumberFormatter::CURRENCY, '$500,100.05', '500100.05'],
            [new NumberFormat($this->numberFormats['en']), new Currency($this->currencies['USD']), NumberFormatter::CURRENCY, '-$1,059.59', '-1059.59'],
            [new NumberFormat($this->numberFormats['en']), new Currency($this->currencies['USD']), NumberFormatter::CURRENCY_ACCOUNTING, '($1,059.59)', '-1059.59'],
            [new NumberFormat($this->numberFormats['bn']), new Currency($this->currencies['BND']), NumberFormatter::CURRENCY, '৫,০০,১০০.০৫BND', '500100.05'],
        ];
    }
}
