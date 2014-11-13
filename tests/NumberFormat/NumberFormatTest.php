<?php

namespace CommerceGuys\Intl\Tests\NumberFormat;

use CommerceGuys\Intl\NumberFormat\NumberFormat;

/**
 * @coversDefaultClass CommerceGuys\Intl\NumberFormat\NumberFormat
 */
class NumberFormatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NumberFormat
     */
    protected $numberFormat;

    public function setUp()
    {
        $this->numberFormat = new NumberFormat();
    }

    /**
     * @covers ::getLocale
     * @covers ::setLocale
     */
    public function testLocale()
    {
        $this->numberFormat->setLocale('en');
        $this->assertEquals('en', $this->numberFormat->getLocale());
    }

    /**
     * @covers ::getNumberingSystem
     * @covers ::setNumberingSystem
     */
    public function testNumberingSystem()
    {
        $this->numberFormat->setNumberingSystem('latn');
        $this->assertEquals('latn', $this->numberFormat->getNumberingSystem());
    }

    /**
     * @covers ::getDecimalSeparator
     * @covers ::setDecimalSeparator
     */
    public function testDecimalSeparator()
    {
        $this->numberFormat->setDecimalSeparator('.');
        $this->assertEquals('.', $this->numberFormat->getDecimalSeparator());
    }

    /**
     * @covers ::getGroupingSeparator
     * @covers ::setGroupingSeparator
     */
    public function testGroupingSeparator()
    {
        $this->numberFormat->setGroupingSeparator(',');
        $this->assertEquals(',', $this->numberFormat->getGroupingSeparator());
    }

    /**
     * @covers ::getPlusSign
     * @covers ::setPlusSign
     */
    public function testPlusSign()
    {
        $this->numberFormat->setPlusSign('+');
        $this->assertEquals('+', $this->numberFormat->getPlusSign());
    }

    /**
     * @covers ::getMinusSign
     * @covers ::setMinusSign
     */
    public function testMinusSign()
    {
        $this->numberFormat->setMinusSign('-');
        $this->assertEquals('-', $this->numberFormat->getMinusSign());
    }

    /**
     * @covers ::getPercentSign
     * @covers ::setPercentSign
     */
    public function testPercentSign()
    {
        $this->numberFormat->setPercentSign('%');
        $this->assertEquals('%', $this->numberFormat->getPercentSign());
    }

    /**
     * @covers ::getDecimalPattern
     * @covers ::setDecimalPattern
     */
    public function testDecimalPattern()
    {
        $this->numberFormat->setDecimalPattern('#,##0.###');
        $this->assertEquals('#,##0.###', $this->numberFormat->getDecimalPattern());
    }

    /**
     * @covers ::getPercentPattern
     * @covers ::setPercentPattern
     */
    public function testPercentPattern()
    {
        $this->numberFormat->setPercentPattern('#,##0%');
        $this->assertEquals('#,##0%', $this->numberFormat->getPercentPattern());
    }

    /**
     * @covers ::getCurrencyPattern
     * @covers ::setCurrencyPattern
     */
    public function testCurrencyPattern()
    {
        $this->numberFormat->setCurrencyPattern('¤#,##0.00');
        $this->assertEquals('¤#,##0.00', $this->numberFormat->getCurrencyPattern());
    }

    /**
     * @covers ::getAccountingCurrencyPattern
     * @covers ::setAccountingCurrencyPattern
     */
    public function testAccountingCurrencyPattern()
    {
        $this->numberFormat->setAccountingCurrencyPattern('¤#,##0.00;(¤#,##0.00)');
        $this->assertEquals('¤#,##0.00;(¤#,##0.00)', $this->numberFormat->getAccountingCurrencyPattern());
    }
}
