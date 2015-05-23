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
    protected $englishDefinition = [
        'numbering_system' => 'latn',
        'decimal_pattern' => '#,##0.###',
        'percent_pattern' => '#,##0%',
        'currency_pattern' => '¤#,##0.00',
        'accounting_currency_pattern' => '¤#,##0.00;(¤#,##0.00)',
    ];

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
        $this->assertEquals('vfs://resources/number_format/', $definitionPath);

        return $numberFormatRepository;
    }

    /**
     * @covers ::get
     * @covers ::createNumberFormatFromDefinition
     *
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     * @uses \CommerceGuys\Intl\LocaleResolverTrait
     * @depends testConstructor
     */
    public function testGet($numberFormatRepository)
    {
        $numberFormat = $numberFormatRepository->get('en');
        $this->assertInstanceOf('CommerceGuys\\Intl\\NumberFormat\\NumberFormat', $numberFormat);
        $this->assertEquals('en', $numberFormat->getLocale());
        $this->assertEquals('latn', $numberFormat->getNumberingSystem());
        $this->assertEquals('.', $numberFormat->getDecimalSeparator());
        $this->assertEquals(',', $numberFormat->getGroupingSeparator());
        $this->assertEquals('+', $numberFormat->getPlusSign());
        $this->assertEquals('-', $numberFormat->getMinusSign());
        $this->assertEquals('%', $numberFormat->getPercentSign());
        $this->assertEquals('#,##0.###', $numberFormat->getDecimalPattern());
        $this->assertEquals('#,##0%', $numberFormat->getPercentPattern());
        $this->assertEquals('¤#,##0.00', $numberFormat->getCurrencyPattern());
        $this->assertEquals('¤#,##0.00;(¤#,##0.00)', $numberFormat->getAccountingCurrencyPattern());

        return $numberFormat;
    }
}
