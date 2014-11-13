<?php

namespace CommerceGuys\Intl\Tests\Country;

use CommerceGuys\Intl\Country\Country;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Country\Country
 */
class CountryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Country
     */
    protected $country;

    public function setUp()
    {
        $this->country = new Country();
    }

    /**
     * @covers ::getCountryCode
     * @covers ::setCountryCode
     * @covers ::__toString
     */
    public function testCountryCode()
    {
        $this->country->setCountryCode('US');
        $this->assertEquals('US', $this->country->getCountryCode());
        $this->assertEquals('US', (string) $this->country);
    }

    /**
     * @covers ::getName
     * @covers ::setName
     */
    public function testName()
    {
        $this->country->setName('United States');
        $this->assertEquals('United States', $this->country->getName());
    }

    /**
     * @covers ::getThreeLetterCode
     * @covers ::setThreeLetterCode
     */
    public function testThreeLetterCode()
    {
        $this->country->setThreeLetterCode('USA');
        $this->assertEquals('USA', $this->country->getThreeLetterCode());
    }

    /**
     * @covers ::getNumericCode
     * @covers ::setNumericCode
     */
    public function testNumericCode()
    {
        $this->country->setNumericCode('840');
        $this->assertEquals('840', $this->country->getNumericCode());
    }

    /**
     * @covers ::getTelephoneCode
     * @covers ::setTelephoneCode
     */
    public function testTelephoneCode()
    {
        $this->country->setTelephoneCode('1');
        $this->assertEquals('1', $this->country->getTelephoneCode());
    }

    /**
     * @covers ::getLocale
     * @covers ::setLocale
     */
    public function testLocale()
    {
        $this->country->setLocale('en');
        $this->assertEquals('en', $this->country->getLocale());
    }
}
