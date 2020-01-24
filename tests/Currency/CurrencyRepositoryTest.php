<?php

namespace CommerceGuys\Intl\Tests\Currency;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\Currency\CurrencyRepository;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Currency\CurrencyRepository
 */
final class CurrencyRepositoryTest extends TestCase
{
    /**
     * Currency definitions.
     *
     * @var array
     */
    protected $definitions = [
        'en' => [
            'RSD' => [
                'name' => 'Serbian Dinar',
            ],
            'USD' => [
                'name' => 'US Dollar',
                'symbol' => '$',
            ],
        ],
        'es' => [
            'RSD' => [
                'name' => 'dinar serbio',
            ],
            'USD' => [
                'name' => 'dólar estadounidense',
                'symbol' => 'US$',
            ],
        ],
        'de' => [
            'RSD' => [
                'name' => 'Serbischer Dinar',
            ],
            'USD' => [
                'name' => 'US-Dollar',
                'symbol' => '$',
            ],
        ],
    ];

    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        // Mock the existence of JSON definitions on the filesystem.
        $root = vfsStream::setup('resources');
        foreach ($this->definitions as $locale => $data) {
            vfsStream::newFile('currency/' . $locale . '.json')->at($root)->setContent(json_encode($data));
        }

        // Instantiate the currency repository and confirm that the definition path
        // was properly set.
        $currencyRepository = new CurrencyRepository('de', 'en', 'vfs://resources/currency/');
        $definitionPath = $this->getObjectAttribute($currencyRepository, 'definitionPath');
        $this->assertEquals('vfs://resources/currency/', $definitionPath);

        return $currencyRepository;
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
        // Explicit locale.
        $currency = $currencyRepository->get('USD', 'es');
        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals('USD', $currency->getCurrencyCode());
        $this->assertEquals('dólar estadounidense', $currency->getName());
        $this->assertEquals('840', $currency->getNumericCode());
        $this->assertEquals('2', $currency->getFractionDigits());
        $this->assertEquals('US$', $currency->getSymbol());
        $this->assertEquals('es', $currency->getLocale());

        // Default locale, lowercase currency code.
        $currency = $currencyRepository->get('usd');
        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals('USD', $currency->getCurrencyCode());
        $this->assertEquals('US-Dollar', $currency->getName());
        $this->assertEquals('$', $currency->getSymbol());
        $this->assertEquals('de', $currency->getLocale());

        // Fallback locale.
        $currency = $currencyRepository->get('USD', 'INVALID-LOCALE');
        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals('USD', $currency->getCurrencyCode());
        $this->assertEquals('US Dollar', $currency->getName());
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
        // Explicit locale.
        $currencies = $currencyRepository->getAll('es');
        $this->assertArrayHasKey('RSD', $currencies);
        $this->assertArrayHasKey('USD', $currencies);
        $this->assertEquals('dinar serbio', $currencies['RSD']->getName());
        $this->assertEquals('dólar estadounidense', $currencies['USD']->getName());
        $this->assertEquals('RSD', $currencies['RSD']->getSymbol());
        $this->assertEquals('US$', $currencies['USD']->getSymbol());

        // Default locale.
        $currencies = $currencyRepository->getAll();
        $this->assertArrayHasKey('RSD', $currencies);
        $this->assertArrayHasKey('USD', $currencies);
        $this->assertEquals('Serbischer Dinar', $currencies['RSD']->getName());
        $this->assertEquals('US-Dollar', $currencies['USD']->getName());
        $this->assertEquals('RSD', $currencies['RSD']->getSymbol());
        $this->assertEquals('$', $currencies['USD']->getSymbol());

        // Fallback locale.
        $currencies = $currencyRepository->getAll('INVALID-LOCALE');
        $this->assertArrayHasKey('RSD', $currencies);
        $this->assertArrayHasKey('USD', $currencies);
        $this->assertEquals('Serbian Dinar', $currencies['RSD']->getName());
        $this->assertEquals('US Dollar', $currencies['USD']->getName());
        $this->assertEquals('RSD', $currencies['RSD']->getSymbol());
        $this->assertEquals('$', $currencies['USD']->getSymbol());
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
        // Explicit locale.
        $list = $currencyRepository->getList('es');
        $this->assertEquals(['RSD' => 'dinar serbio', 'USD' => 'dólar estadounidense'], $list);

        // Default locale.
        $list = $currencyRepository->getList();
        $this->assertEquals(['RSD' => 'Serbischer Dinar', 'USD' => 'US-Dollar'], $list);

        // Fallback locale.
        $list = $currencyRepository->getList('INVALID-LOCALE');
        $this->assertEquals(['RSD' => 'Serbian Dinar', 'USD' => 'US Dollar'], $list);
    }
}
