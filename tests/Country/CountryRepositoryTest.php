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
     * Base country definitions.
     *
     * @var array
     */
    protected $baseDefinitions = [
        'FR' => [
            'three_letter_code' => 'FRA',
            'numeric_code' => '250',
            'currency_code' => 'EUR',
        ],
        'US' => [
            'three_letter_code' => 'USA',
            'numeric_code' => '840',
            'currency_code' => 'USD',
        ],
    ];

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
        vfsStream::newFile('country/base.json')->at($root)->setContent(json_encode($this->baseDefinitions));
        vfsStream::newFile('country/en.json')->at($root)->setContent(json_encode($this->englishDefinitions));

        // Instantiate the country repository and confirm that the definition path
        // was properly set.
        $countryRepository = new CountryRepository('vfs://resources/country/');
        $definitionPath = $this->getObjectAttribute($countryRepository, 'definitionPath');
        $this->assertEquals('vfs://resources/country/', $definitionPath);

        return $countryRepository;
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     * @covers ::createCountryFromDefinition
     *
     * @uses \CommerceGuys\Intl\Country\Country
     * @uses \CommerceGuys\Intl\LocaleResolverTrait
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
     * @uses \CommerceGuys\Intl\LocaleResolverTrait
     * @expectedException \CommerceGuys\Intl\Exception\UnknownCountryException
     * @depends testConstructor
     */
    public function testGetInvalidCountry($countryRepository)
    {
        $countryRepository->get('DE');
    }

    /**
     * @covers ::getAll
     * @covers ::loadDefinitions
     * @covers ::createCountryFromDefinition
     *
     * @uses \CommerceGuys\Intl\Country\Country
     * @uses \CommerceGuys\Intl\LocaleResolverTrait
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
     * @uses \CommerceGuys\Intl\LocaleResolverTrait
     * @depends testConstructor
     */
    public function testGetList($countryRepository)
    {
        $list = $countryRepository->getList();
        $expectedList = ['FR' => 'France', 'US' => 'United States'];
        $this->assertEquals($expectedList, $list);
    }
}
