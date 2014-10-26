<?php

namespace CommerceGuys\Intl\Tests\NumberFormat;

use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \CommerceGuys\Intl\NumberFormat\NumberFormatRepository
 */
class NumberFormatRepositoryTest extends \PHPUnit_Framework_TestCase
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

        // Instantiate the number format repository and confirm that the definition
        // path was properly set.
        $numberFormatRepository = new NumberFormatRepository('vfs://resources/number_format/');
        $definitionPath = $this->getObjectAttribute($numberFormatRepository, 'definitionPath');
        $this->assertEquals($definitionPath, 'vfs://resources/number_format/');

        return $numberFormatRepository;
    }

    /**
     * @covers ::get
     * @covers ::createNumberFormatFromDefinition
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     * @uses \CommerceGuys\Intl\LocaleResolverTrait
     * @depends testConstructor
     */
    public function testGet($numberFormatRepository)
    {
        $numberFormat = $numberFormatRepository->get('en');
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
