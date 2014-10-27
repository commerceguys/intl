<?php

namespace CommerceGuys\Intl\Tests\Currency;

use CommerceGuys\Intl\Currency\Currency;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Currency\Currency
 */
class CurrencyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Currency
     */
    protected $currency;

    public function setUp()
    {
        $this->currency = new Currency();
    }

    /**
     * @covers ::getCurrencyCode
     * @covers ::setCurrencyCode
     * @covers ::__toString
     */
    public function testCurrencyCode()
    {
        $this->currency->setCurrencyCode('USD');
        $this->assertEquals($this->currency->getCurrencyCode(), 'USD');
        $this->assertEquals((string) $this->currency, 'USD');
    }

    /**
     * @covers ::getName
     * @covers ::setName
     */
    public function testName()
    {
        $this->currency->setName('US Dollar');
        $this->assertEquals($this->currency->getName(), 'US Dollar');
    }

    /**
     * @covers ::getNumericCode
     * @covers ::setNumericCode
     */
    public function testNumericCode()
    {
        $this->currency->setNumericCode('840');
        $this->assertEquals($this->currency->getNumericCode(), '840');
    }

    /**
     * @covers ::getFractionDigits
     * @covers ::setFractionDigits
     */
    public function testFractionDigits()
    {
        $this->currency->setFractionDigits('2');
        $this->assertEquals($this->currency->getFractionDigits(), '2');
    }

    /**
     * @covers ::getSymbol
     * @covers ::setSymbol
     */
    public function testSymbol()
    {
        $this->currency->setSymbol('$');
        $this->assertEquals($this->currency->getSymbol(), '$');
    }

    /**
     * @covers ::getLocale
     * @covers ::setLocale
     */
    public function testLocale()
    {
        $this->currency->setLocale('en');
        $this->assertEquals($this->currency->getLocale(), 'en');
    }
}
