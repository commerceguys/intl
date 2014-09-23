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
        // Mock the existence of JSON definitions on the filesystem.
        $root = vfsStream::setup('resources');
        vfsStream::newFile('country/base.json')->at($root)->setContent(json_encode($this->baseDefinitions));
        vfsStream::newFile('country/en.json')->at($root)->setContent(json_encode($this->englishDefinitions));

        // Instantiate the country repository and confirm that the definition path
        // was properly set.
        $countryRepository = new CountryRepository('vfs://resources/country/');
        $definitionPath = $this->getObjectAttribute($countryRepository, 'definitionPath');
        $this->assertEquals($definitionPath, 'vfs://resources/country/');

        return $countryRepository;
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     * @covers ::createCountryFromDefinition
     * @uses \CommerceGuys\Intl\Country\Country::getCountryCode
     * @uses \CommerceGuys\Intl\Country\Country::setCountryCode
     * @uses \CommerceGuys\Intl\Country\Country::getName
     * @uses \CommerceGuys\Intl\Country\Country::setName
     * @uses \CommerceGuys\Intl\Country\Country::getThreeLetterCode
     * @uses \CommerceGuys\Intl\Country\Country::setThreeLetterCode
     * @uses \CommerceGuys\Intl\Country\Country::getNumericCode
     * @uses \CommerceGuys\Intl\Country\Country::setNumericCode
     * @uses \CommerceGuys\Intl\Country\Country::getTelephoneCode
     * @uses \CommerceGuys\Intl\Country\Country::setTelephoneCode
     * @uses \CommerceGuys\Intl\Country\Country::getLocale
     * @uses \CommerceGuys\Intl\Country\Country::setLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::resolveLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::getLocaleVariants
     * @depends testConstructor
     */
    public function testGet($countryRepository)
    {
        $country = $countryRepository->get('FR');
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
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::resolveLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::getLocaleVariants
     * @expectedException \CommerceGuys\Intl\Country\UnknownCountryException
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
     * @uses \CommerceGuys\Intl\Country\Country::getCountryCode
     * @uses \CommerceGuys\Intl\Country\Country::setCountryCode
     * @uses \CommerceGuys\Intl\Country\Country::setName
     * @uses \CommerceGuys\Intl\Country\Country::setThreeLetterCode
     * @uses \CommerceGuys\Intl\Country\Country::setNumericCode
     * @uses \CommerceGuys\Intl\Country\Country::setTelephoneCode
     * @uses \CommerceGuys\Intl\Country\Country::setLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::resolveLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::getLocaleVariants
     * @depends testConstructor
     */
    public function testGetAll($countryRepository)
    {
        $countries = $countryRepository->getAll();
        $this->assertArrayHasKey('FR', $countries);
        $this->assertArrayHasKey('US', $countries);
        $this->assertEquals($countries['FR']->getCountryCode(), 'FR');
        $this->assertEquals($countries['US']->getCountryCode(), 'US');
    }
}
