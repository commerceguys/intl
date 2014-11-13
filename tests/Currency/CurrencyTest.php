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
        $this->assertEquals('USD', $this->currency->getCurrencyCode());
        $this->assertEquals('USD', (string) $this->currency);
    }

    /**
     * @covers ::getName
     * @covers ::setName
     */
    public function testName()
    {
        $this->currency->setName('US Dollar');
        $this->assertEquals('US Dollar', $this->currency->getName());
    }

    /**
     * @covers ::getNumericCode
     * @covers ::setNumericCode
     */
    public function testNumericCode()
    {
        $this->currency->setNumericCode('840');
        $this->assertEquals('840', $this->currency->getNumericCode());
    }

    /**
     * @covers ::getFractionDigits
     * @covers ::setFractionDigits
     */
    public function testFractionDigits()
    {
        $this->currency->setFractionDigits('2');
        $this->assertEquals('2', $this->currency->getFractionDigits());
    }

    /**
     * @covers ::getSymbol
     * @covers ::setSymbol
     */
    public function testSymbol()
    {
        $this->currency->setSymbol('$');
        $this->assertEquals('$', $this->currency->getSymbol());
    }

    /**
     * @covers ::getLocale
     * @covers ::setLocale
     */
    public function testLocale()
    {
        $this->currency->setLocale('en');
        $this->assertEquals('en', $this->currency->getLocale());
    }
}
