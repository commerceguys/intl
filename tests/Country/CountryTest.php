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
        $this->country = new Country;
    }

    /**
     * @covers ::getCountryCode
     * @covers ::setCountryCode
     * @covers ::__toString
     */
    public function testCountryCode()
    {
        $this->country->setCountryCode('US');
        $this->assertEquals($this->country->getCountryCode(), 'US');
        $this->assertEquals((string) $this->country, 'US');
    }

    /**
     * @covers ::getName
     * @covers ::setName
     */
    public function testName()
    {
        $this->country->setName('United States');
        $this->assertEquals($this->country->getName(), 'United States');
    }

    /**
     * @covers ::getThreeLetterCode
     * @covers ::setThreeLetterCode
     */
    public function testThreeLetterCode()
    {
        $this->country->setThreeLetterCode('USA');
        $this->assertEquals($this->country->getThreeLetterCode(), 'USA');
    }

    /**
     * @covers ::getNumericCode
     * @covers ::setNumericCode
     */
    public function testNumericCode()
    {
        $this->country->setNumericCode('840');
        $this->assertEquals($this->country->getNumericCode(), '840');
    }

    /**
     * @covers ::getTelephoneCode
     * @covers ::setTelephoneCode
     */
    public function testTelephoneCode()
    {
        $this->country->setTelephoneCode('1');
        $this->assertEquals($this->country->getTelephoneCode(), '1');
    }

    /**
     * @covers ::getLocale
     * @covers ::setLocale
     */
    public function testLocale()
    {
        $this->country->setLocale('en');
        $this->assertEquals($this->country->getLocale(), 'en');
    }
}
