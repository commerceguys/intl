<?php

namespace CommerceGuys\Intl\Tests\Currency;

use CommerceGuys\Intl\Currency\CurrencyRepository;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Currency\CurrencyRepository
 */
class CurrencyRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * English currency definitions.
     *
     * @var array
     */
    protected $englishDefinitions = [
        'USD' => [
            'name' => 'US Dollar',
            'symbol' => '$',
        ],
        'EUR' => [
            'name' => 'Euro',
            'symbol' => '€',
        ],
    ];

    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        // Mock the existence of JSON definitions on the filesystem.
        $root = vfsStream::setup('resources');
        vfsStream::newFile('currency/en.json')->at($root)->setContent(json_encode($this->englishDefinitions));

        // Instantiate the currency repository and confirm that the definition path
        // was properly set.
        $currencyRepository = new CurrencyRepository('vfs://resources/currency/');
        $definitionPath = $this->getObjectAttribute($currencyRepository, 'definitionPath');
        $this->assertEquals('vfs://resources/currency/', $definitionPath);

        return $currencyRepository;
    }

    /**
     * @covers ::getDefaultLocale
     * @covers ::setDefaultLocale
     * @covers ::getFallbackLocale
     * @covers ::setFallbackLocale
     *
     * @depends testConstructor
     */
    public function testLocale($currencyRepository)
    {
        $this->assertEquals('en', $currencyRepository->getDefaultLocale());
        $currencyRepository->setDefaultLocale('fr');
        $this->assertEquals('fr', $currencyRepository->getDefaultLocale());
        // Revert the value for the other tests.
        $currencyRepository->setDefaultLocale('en');

        $this->assertNull($currencyRepository->getFallbackLocale());
        $currencyRepository->setFallbackLocale('en');
        $this->assertEquals('en', $currencyRepository->getFallbackLocale());
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     * @covers ::createCurrencyFromDefinition
     *
     * @uses \CommerceGuys\Intl\Currency\Currency
     * @uses \CommerceGuys\Intl\Locale
     * @depends testConstructor
     */
    public function testGet($currencyRepository)
    {
        $currency = $currencyRepository->get('USD');
        $this->assertInstanceOf('CommerceGuys\\Intl\\Currency\\Currency', $currency);
        $this->assertEquals('USD', $currency->getCurrencyCode());
        $this->assertEquals('US Dollar', $currency->getName());
        $this->assertEquals('840', $currency->getNumericCode());
        $this->assertEquals('2', $currency->getFractionDigits());
        $this->assertEquals('$', $currency->getSymbol());
        $this->assertEquals('en', $currency->getLocale());
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     *
     * @uses \CommerceGuys\Intl\Locale
     * @expectedException \CommerceGuys\Intl\Exception\UnknownCurrencyException
     * @depends testConstructor
     */
    public function testGetInvalidCurrency($currencyRepository)
    {
        $currencyRepository->get('INVALID');
    }

    /**
     * @covers ::getAll
     * @covers ::loadDefinitions
     * @covers ::createCurrencyFromDefinition
     *
     * @uses \CommerceGuys\Intl\Currency\Currency
     * @uses \CommerceGuys\Intl\Locale
     * @depends testConstructor
     */
    public function testGetAll($currencyRepository)
    {
        $currencies = $currencyRepository->getAll();
        $this->assertArrayHasKey('USD', $currencies);
        $this->assertArrayHasKey('EUR', $currencies);
        $this->assertEquals('USD', $currencies['USD']->getCurrencyCode());
        $this->assertEquals('EUR', $currencies['EUR']->getCurrencyCode());
    }

    /**
     * @covers ::getList
     * @covers ::loadDefinitions
     *
     * @uses \CommerceGuys\Intl\Locale
     * @depends testConstructor
     */
    public function testGetList($currencyRepository)
    {
        $list = $currencyRepository->getList();
        $expectedList = ['EUR' => 'Euro', 'USD' => 'US Dollar'];
        $this->assertEquals($expectedList, $list);
    }
}
