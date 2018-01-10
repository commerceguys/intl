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
     * @covers ::getDefaultLocale
     * @covers ::setDefaultLocale
     * @covers ::getFallbackLocale
     * @covers ::setFallbackLocale
     */
    public function testLocale()
    {
        $numberFormatRepository = new NumberFormatRepository();
        $this->assertEquals('en', $numberFormatRepository->getDefaultLocale());
        $numberFormatRepository->setDefaultLocale('fr');
        $this->assertEquals('fr', $numberFormatRepository->getDefaultLocale());

        $this->assertEquals('en', $numberFormatRepository->getFallbackLocale());
        $numberFormatRepository->setFallbackLocale('de');
        $this->assertEquals('de', $numberFormatRepository->getFallbackLocale());
    }

    /**
     * @covers ::get
     * @covers ::createNumberFormatFromDefinition
     *
     * @uses \CommerceGuys\Intl\NumberFormat\NumberFormat
     * @uses \CommerceGuys\Intl\Locale
     */
    public function testGet()
    {
        $numberFormatRepository = new NumberFormatRepository();

        $numberFormat = $numberFormatRepository->get('en');
        $this->assertInstanceOf('CommerceGuys\\Intl\\NumberFormat\\NumberFormat', $numberFormat);
        $this->assertEquals('en', $numberFormat->getLocale());
        $this->assertEquals('latn', $numberFormat->getNumberingSystem());
        $this->assertEquals('#,##0.###', $numberFormat->getDecimalPattern());
        $this->assertEquals('#,##0%', $numberFormat->getPercentPattern());
        $this->assertEquals('¤#,##0.00', $numberFormat->getCurrencyPattern());
        $this->assertEquals('¤#,##0.00;(¤#,##0.00)', $numberFormat->getAccountingCurrencyPattern());
        $this->assertEquals('.', $numberFormat->getDecimalSeparator());
        $this->assertEquals(',', $numberFormat->getGroupingSeparator());
        $this->assertEquals('+', $numberFormat->getPlusSign());
        $this->assertEquals('-', $numberFormat->getMinusSign());
        $this->assertEquals('%', $numberFormat->getPercentSign());

        $numberFormat = $numberFormatRepository->get('es');
        $this->assertEquals('es', $numberFormat->getLocale());
    }
}
