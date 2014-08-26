<?php

namespace CommerceGuys\Intl\Tests\Country;

use CommerceGuys\Intl\Country\DefaultCountryManager;
use Symfony\Component\Yaml\Dumper;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass CommerceGuys\Intl\Country\DefaultCountryManager
 */
class DefaultCountryManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Base country definitions.
     *
     * @var array
     */
    protected $baseDefinitions = array(
        'FR' => array(
            'code' => 'FR',
            'three_letter_code' => 'FRA',
            'numeric_code' => '250',
            'telephone_code' => '33',
        ),
        'US' => array(
            'code' => 'US',
            'three_letter_code' => 'USA',
            'numeric_code' => '840',
            'telephone_code' => '1',
        ),
    );

    /**
     * English country definitions.
     *
     * @var array
     */
    protected $englishDefinitions = array(
        'FR' => array(
            'name' => 'France',
        ),
        'US' => array(
            'name' => 'United States',
        ),
    );

    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        // Mock the existence of YAML definitions on the filesystem.
        $dumper = new Dumper;
        $root = vfsStream::setup('resources');
        vfsStream::newFile('country/base.yml')->at($root)->setContent($dumper->dump($this->baseDefinitions));
        vfsStream::newFile('country/en.yml')->at($root)->setContent($dumper->dump($this->englishDefinitions));

        // Instantiate the country manager and confirm that the parsed
        // base definitions match the starting ones.
        $countryManager = new DefaultCountryManager('vfs://resources/country/');
        $baseDefinitions = $this->getObjectAttribute($countryManager, 'baseDefinitions');
        $this->assertEquals($baseDefinitions, $this->baseDefinitions);

        return $countryManager;
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     * @covers ::createCountryFromDefinition
     * @depends testConstructor
     */
    public function testGet($countryManager)
    {
        $country = $countryManager->get('FR');
        $this->assertInstanceOf('CommerceGuys\\Intl\\Country\\Country', $country);
        $this->assertEquals($country->getCountryCode(), 'FR');
        $this->assertEquals($country->getName(), 'France');
        $this->assertEquals($country->getThreeLetterCode(), 'FRA');
        $this->assertEquals($country->getNumericCode(), '250');
        $this->assertEquals($country->getTelephoneCode(), '33');
        $this->assertEquals($country->getLocale(), 'en');
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     * @expectedException \CommerceGuys\Intl\Country\UnknownCountryException
     * @depends testConstructor
     */
    public function testGetInvalidCountry($countryManager)
    {
        $countryManager->get('DE');
    }

    /**
     * @covers ::getAll
     * @covers ::loadDefinitions
     * @covers ::createCountryFromDefinition
     * @uses CommerceGuys\Intl\Country\Country::getCountryCode
     * @uses CommerceGuys\Intl\Country\Country::setCountryCode
     * @uses CommerceGuys\Intl\Country\Country::setName
     * @uses CommerceGuys\Intl\Country\Country::setThreeLetterCode
     * @uses CommerceGuys\Intl\Country\Country::setNumericCode
     * @uses CommerceGuys\Intl\Country\Country::setTelephoneCode
     * @uses CommerceGuys\Intl\Country\Country::setLocale
     * @depends testConstructor
     */
    public function testGetAll($countryManager)
    {
        $countries = $countryManager->getAll();
        $this->assertArrayHasKey('FR', $countries);
        $this->assertArrayHasKey('US', $countries);
        $this->assertEquals($countries['FR']->getCountryCode(), 'FR');
        $this->assertEquals($countries['US']->getCountryCode(), 'US');
    }
}
