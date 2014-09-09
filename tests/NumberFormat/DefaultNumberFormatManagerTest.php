<?php

namespace CommerceGuys\Intl\Tests\NumberFormat;

use CommerceGuys\Intl\NumberFormat\DefaultNumberFormatManager;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \CommerceGuys\Intl\NumberFormat\DefaultNumberFormatManager
 */
class DefaultNumberFormatManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * English number format definition.
     *
     * @var array
     */
    protected $englishDefinition = array(
        'numbering_system' => 'latn',
        'decimal_pattern' => '#,##0.###',
        'percent_pattern' => '#,##0%',
        'currency_pattern' => '¤#,##0.00',
        'accounting_currency_pattern' => '¤#,##0.00;(¤#,##0.00)',
    );

    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        // Mock the existence of JSON definitions on the filesystem.
        $root = vfsStream::setup('resources');
        vfsStream::newFile('number_format/en.json')->at($root)->setContent(json_encode($this->englishDefinition));

        // Instantiate the number format manager and confirm that the definition
        // path was properly set.
        $numberFormatManager = new DefaultNumberFormatManager('vfs://resources/number_format/');
        $definitionPath = $this->getObjectAttribute($numberFormatManager, 'definitionPath');
        $this->assertEquals($definitionPath, 'vfs://resources/number_format/');

        return $numberFormatManager;
    }

    /**
     * @covers ::get
     * @covers ::createNumberFormatFromDefinition
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getLocale
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setLocale
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getNumberingSystem
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setNumberingSystem
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getDecimalSeparator
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setDecimalSeparator
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getGroupingSeparator
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setGroupingSeparator
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getPlusSign
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setPlusSign
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getMinusSign
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setMinusSign
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getPercentSign
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setPercentSign
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getDecimalPattern
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setDecimalPattern
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getPercentPattern
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setPercentPattern
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getCurrencyPattern
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setCurrencyPattern
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::getAccountingCurrencyPattern
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat::setAccountingCurrencyPattern
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::resolveLocale
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::getLocaleVariants
     * @depends testConstructor
     */
    public function testGet($numberFormatManager)
    {
        $numberFormat = $numberFormatManager->get('en');
        $this->assertInstanceOf('CommerceGuys\\Intl\\NumberFormat\\NumberFormat', $numberFormat);
        $this->assertEquals($numberFormat->getLocale(), 'en');
        $this->assertEquals($numberFormat->getNumberingSystem(), 'latn');
        $this->assertEquals($numberFormat->getDecimalSeparator(), '.');
        $this->assertEquals($numberFormat->getGroupingSeparator(), ',');
        $this->assertEquals($numberFormat->getPlusSign(), '+');
        $this->assertEquals($numberFormat->getMinusSign(), '-');
        $this->assertEquals($numberFormat->getPercentSign(), '%');
        $this->assertEquals($numberFormat->getDecimalPattern(), '#,##0.###');
        $this->assertEquals($numberFormat->getPercentPattern(), '#,##0%');
        $this->assertEquals($numberFormat->getCurrencyPattern(), '¤#,##0.00');
        $this->assertEquals($numberFormat->getAccountingCurrencyPattern(), '¤#,##0.00;(¤#,##0.00)');

        return $numberFormat;
    }
}
