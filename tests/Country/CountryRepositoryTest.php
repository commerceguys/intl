<?php

namespace CommerceGuys\Intl\Tests\Country;

use CommerceGuys\Intl\Country\CountryRepository;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Country\CountryRepository
 */
class CountryRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * English country definitions.
     *
     * @var array
     */
    protected $englishDefinitions = [
        'FR' => [
            'name' => 'France',
        ],
        'US' => [
            'name' => 'United States',
        ],
    ];

    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        // Mock the existence of JSON definitions on the filesystem.
        $root = vfsStream::setup('resources');
        vfsStream::newFile('country/en.json')->at($root)->setContent(json_encode($this->englishDefinitions));

        // Instantiate the country repository and confirm that the definition path
        // was properly set.
        $countryRepository = new CountryRepository('vfs://resources/country/');
        $definitionPath = $this->getObjectAttribute($countryRepository, 'definitionPath');
        $this->assertEquals('vfs://resources/country/', $definitionPath);

        return $countryRepository;
    }

    /**
     * @covers ::getDefaultLocale
     * @covers ::setDefaultLocale
     * @covers ::getFallbackLocale
     * @covers ::setFallbackLocale
     *
     * @depends testConstructor
     */
    public function testLocale($countryRepository)
    {
        $this->assertEquals('en', $countryRepository->getDefaultLocale());
        $countryRepository->setDefaultLocale('fr');
        $this->assertEquals('fr', $countryRepository->getDefaultLocale());
        // Revert the value for the other tests.
        $countryRepository->setDefaultLocale('en');

        $this->assertNull($countryRepository->getFallbackLocale());
        $countryRepository->setFallbackLocale('en');
        $this->assertEquals('en', $countryRepository->getFallbackLocale());
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     * @covers ::createCountryFromDefinition
     *
     * @uses \CommerceGuys\Intl\Country\Country
     * @uses \CommerceGuys\Intl\Locale
     * @depends testConstructor
     */
    public function testGet($countryRepository)
    {
        $country = $countryRepository->get('FR');
        $this->assertInstanceOf('CommerceGuys\\Intl\\Country\\Country', $country);
        $this->assertEquals('FR', $country->getCountryCode());
        $this->assertEquals('France', $country->getName());
        $this->assertEquals('FRA', $country->getThreeLetterCode());
        $this->assertEquals('250', $country->getNumericCode());
        $this->assertEquals('EUR', $country->getCurrencyCode());
        $this->assertEquals('en', $country->getLocale());
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     *
     * @uses \CommerceGuys\Intl\Locale
     * @expectedException \CommerceGuys\Intl\Exception\UnknownCountryException
     * @depends testConstructor
     */
    public function testGetInvalidCountry($countryRepository)
    {
        $countryRepository->get('INVALID');
    }

    /**
     * @covers ::getAll
     * @covers ::loadDefinitions
     * @covers ::createCountryFromDefinition
     *
     * @uses \CommerceGuys\Intl\Country\Country
     * @uses \CommerceGuys\Intl\Locale
     * @depends testConstructor
     */
    public function testGetAll($countryRepository)
    {
        $countries = $countryRepository->getAll();
        $this->assertArrayHasKey('FR', $countries);
        $this->assertArrayHasKey('US', $countries);
        $this->assertEquals('FR', $countries['FR']->getCountryCode());
        $this->assertEquals('US', $countries['US']->getCountryCode());
    }

    /**
     * @covers ::getList
     * @covers ::loadDefinitions
     *
     * @uses \CommerceGuys\Intl\Locale
     * @depends testConstructor
     */
    public function testGetList($countryRepository)
    {
        $list = $countryRepository->getList();
        $expectedList = ['FR' => 'France', 'US' => 'United States'];
        $this->assertEquals($expectedList, $list);
    }
}
