<?php

namespace CommerceGuys\Intl\Tests\Currency;

use CommerceGuys\Intl\Currency\Currency;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Currency\Currency
 */
final class CurrencyTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testMissingProperty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required property "currency_code".');
        $currency = new Currency([]);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::getCurrencyCode
     * @covers ::getName
     * @covers ::getNumericCode
     * @covers ::getSymbol
     * @covers ::getFractionDigits
     * @covers ::getDisplayFractionDigits
     * @covers ::getLocale
     */
    public function testValid()
    {
        $definition = [
            'currency_code' => 'USD',
            'name' => 'dollar des États-Unis',
            'numeric_code' => '840',
            'symbol' => '$US',
            // Dummy value, intentionally different from the default.
            'fraction_digits' => 3,
            'display_fraction_digits' => 1,
            'locale' => 'fr',
        ];
        $currency = new Currency($definition);

        $this->assertEquals($definition['currency_code'], $currency->__toString());
        $this->assertEquals($definition['currency_code'], $currency->getCurrencyCode());
        $this->assertEquals($definition['name'], $currency->getName());
        $this->assertEquals($definition['numeric_code'], $currency->getNumericCode());
        $this->assertEquals($definition['symbol'], $currency->getSymbol());
        $this->assertEquals($definition['fraction_digits'], $currency->getFractionDigits());
        $this->assertEquals($definition['display_fraction_digits'], $currency->getDisplayFractionDigits());
        $this->assertEquals($definition['locale'], $currency->getLocale());

        // The display fraction digits default to the fraction digits.
        unset($definition['display_fraction_digits']);
        $currency = new Currency($definition);
        $this->assertEquals($definition['fraction_digits'], $currency->getDisplayFractionDigits());
    }
}
