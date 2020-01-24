<?php

namespace CommerceGuys\Intl\Tests\Language;

use CommerceGuys\Intl\Language\Language;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Language\Language
 */
final class LanguageTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testMissingProperty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required property "language_code".');
        $language = new Language([]);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::getLanguageCode
     * @covers ::getName
     * @covers ::getLocale
     */
    public function testValid()
    {
        $definition = [
            'language_code' => 'fr',
            'name' => 'French',
            'locale' => 'en-US',
        ];
        $language = new Language($definition);

        $this->assertEquals($definition['language_code'], $language->__toString());
        $this->assertEquals($definition['language_code'], $language->getLanguageCode());
        $this->assertEquals($definition['name'], $language->getName());
        $this->assertEquals($definition['locale'], $language->getLocale());
    }
}
