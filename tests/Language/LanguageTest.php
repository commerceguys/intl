<?php

namespace CommerceGuys\Intl\Tests\Language;

use CommerceGuys\Intl\Language\Language;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Language\Language
 */
class LanguageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testMissingProperty()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'Missing required property "language_code".');
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
