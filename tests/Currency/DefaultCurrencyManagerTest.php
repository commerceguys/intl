<?php

namespace CommerceGuys\Intl\Tests\Currency;

use CommerceGuys\Intl\Currency\DefaultCurrencyManager;
use Symfony\Component\Yaml\Dumper;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Currency\DefaultCurrencyManager
 */
class DefaultCurrencyManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Base currency definitions.
     *
     * @var array
     */
    protected $baseDefinitions = array(
        'USD' => array(
            'code' => 'USD',
            'numeric_code' => '840',
        ),
        'EUR' => array(
            'code' => 'EUR',
            'numeric_code' => '840',
            'fraction_digits' => '2',
        ),
    );

    /**
     * English currency definitions.
     *
     * @var array
     */
    protected $englishDefinitions = array(
        'USD' => array(
            'name' => 'US Dollar',
            'symbol' => '$',
        ),
        'EUR' => array(
            'name' => 'Euro',
            'symbol' => '€',
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
        vfsStream::newFile('currency/base.yml')->at($root)->setContent($dumper->dump($this->baseDefinitions));
        vfsStream::newFile('currency/en.yml')->at($root)->setContent($dumper->dump($this->englishDefinitions));

        // Instantiate the currency manager and confirm that the definition path
        // was properly set.
        $currencyManager = new DefaultCurrencyManager('vfs://resources/currency/');
        $definitionPath = $this->getObjectAttribute($currencyManager, 'definitionPath');
        $this->assertEquals($definitionPath, 'vfs://resources/currency/');

        return $currencyManager;
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     * @covers ::createCurrencyFromDefinition
     * @uses \CommerceGuys\Intl\Currency\Currency::getCurrencyCode
     * @uses \CommerceGuys\Intl\Currency\Currency::setCurrencyCode
     * @uses \CommerceGuys\Intl\Currency\Currency::getName
     * @uses \CommerceGuys\Intl\Currency\Currency::setName
     * @uses \CommerceGuys\Intl\Currency\Currency::getNumericCode
     * @uses \CommerceGuys\Intl\Currency\Currency::setNumericCode
     * @uses \CommerceGuys\Intl\Currency\Currency::getFractionDigits
     * @uses \CommerceGuys\Intl\Currency\Currency::setFractionDigits
     * @uses \CommerceGuys\Intl\Currency\Currency::getSymbol
     * @uses \CommerceGuys\Intl\Currency\Currency::setSymbol
     * @uses \CommerceGuys\Intl\Currency\Currency::getLocale
     * @uses \CommerceGuys\Intl\Currency\Currency::setLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::resolveLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::getLocaleVariants
     * @depends testConstructor
     */
    public function testGet($currencyManager)
    {
        $currency = $currencyManager->get('USD');
        $this->assertInstanceOf('CommerceGuys\\Intl\\Currency\\Currency', $currency);
        $this->assertEquals($currency->getCurrencyCode(), 'USD');
        $this->assertEquals($currency->getName(), 'US Dollar');
        $this->assertEquals($currency->getNumericCode(), '840');
        $this->assertEquals($currency->getFractionDigits(), '2');
        $this->assertEquals($currency->getSymbol(), '$');
        $this->assertEquals($currency->getLocale(), 'en');
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::resolveLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::getLocaleVariants
     * @expectedException \CommerceGuys\Intl\Currency\UnknownCurrencyException
     * @depends testConstructor
     */
    public function testGetInvalidCurrency($currencyManager)
    {
        $currencyManager->get('RSD');
    }

    /**
     * @covers ::getAll
     * @covers ::loadDefinitions
     * @covers ::createCurrencyFromDefinition
     * @uses \CommerceGuys\Intl\Currency\Currency::getCurrencyCode
     * @uses \CommerceGuys\Intl\Currency\Currency::setCurrencyCode
     * @uses \CommerceGuys\Intl\Currency\Currency::setName
     * @uses \CommerceGuys\Intl\Currency\Currency::setNumericCode
     * @uses \CommerceGuys\Intl\Currency\Currency::setFractionDigits
     * @uses \CommerceGuys\Intl\Currency\Currency::setSymbol
     * @uses \CommerceGuys\Intl\Currency\Currency::setLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::resolveLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::getLocaleVariants
     * @depends testConstructor
     */
    public function testGetAll($currencyManager)
    {
        $currencies = $currencyManager->getAll();
        $this->assertArrayHasKey('USD', $currencies);
        $this->assertArrayHasKey('EUR', $currencies);
        $this->assertEquals($currencies['USD']->getCurrencyCode(), 'USD');
        $this->assertEquals($currencies['EUR']->getCurrencyCode(), 'EUR');
    }
}
