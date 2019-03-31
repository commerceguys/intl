<?php

namespace CommerceGuys\Intl\Tests\NumberFormat;

use CommerceGuys\Intl\NumberFormat\NumberFormat;

/**
 * @coversDefaultClass \CommerceGuys\Intl\NumberFormat\NumberFormat
 */
class NumberFormatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testMissingProperty()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'Missing required property "locale".');
        $numberFormat = new NumberFormat([]);
    }

    /**
     * @covers ::__construct
     */
    public function testInvalidNumberingSystem()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'Invalid numbering system "FAKE".');
        $numberFormat = new NumberFormat([
            'locale' => 'sr-Latn',
            'decimal_pattern' => '#,##0.###',
            'percent_pattern' => '#,##0%',
            'currency_pattern' => '#,##0.00 ¤',
            'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
            'numbering_system' => 'FAKE',
            'decimal_separator' => ',',
            'grouping_separator' => '.',
            // Dummy values, intentionally different from the defaults.
            'plus_sign' => '++',
            'minus_sign' => '--',
            'percent_sign' => '%%',
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::getLocale
     * @covers ::getDecimalPattern
     * @covers ::getPercentPattern
     * @covers ::getCurrencyPattern
     * @covers ::getAccountingCurrencyPattern
     * @covers ::getNumberingSystem
     * @covers ::getDecimalSeparator
     * @covers ::getGroupingSeparator
     * @covers ::getPlusSign
     * @covers ::getMinusSign
     * @covers ::getPercentSign
     */
    public function testValid()
    {
        $definition = [
            'locale' => 'sr-Latn',
            'decimal_pattern' => '#,##0.###',
            'percent_pattern' => '#,##0%',
            'currency_pattern' => '#,##0.00 ¤',
            'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
            'numbering_system' => 'latn',
            'decimal_separator' => ',',
            'grouping_separator' => '.',
            // Dummy values, intentionally different from the defaults.
            'plus_sign' => '++',
            'minus_sign' => '--',
            'percent_sign' => '%%',
        ];
        $numberFormat = new NumberFormat($definition);

        $this->assertEquals($definition['locale'], $numberFormat->getLocale());
        $this->assertEquals($definition['decimal_pattern'], $numberFormat->getDecimalPattern());
        $this->assertEquals($definition['percent_pattern'], $numberFormat->getPercentPattern());
        $this->assertEquals($definition['currency_pattern'], $numberFormat->getCurrencyPattern());
        $this->assertEquals($definition['accounting_currency_pattern'], $numberFormat->getAccountingCurrencyPattern());
        $this->assertEquals($definition['numbering_system'], $numberFormat->getNumberingSystem());
        $this->assertEquals($definition['decimal_separator'], $numberFormat->getDecimalSeparator());
        $this->assertEquals($definition['grouping_separator'], $numberFormat->getGroupingSeparator());
        $this->assertEquals($definition['plus_sign'], $numberFormat->getPlusSign());
        $this->assertEquals($definition['minus_sign'], $numberFormat->getMinusSign());
        $this->assertEquals($definition['percent_sign'], $numberFormat->getPercentSign());
    }
}
