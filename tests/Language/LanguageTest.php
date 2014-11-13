<?php

namespace CommerceGuys\Intl\Tests\Language;

use CommerceGuys\Intl\Language\Language;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Language\Language
 */
class LanguageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Language
     */
    protected $language;

    public function setUp()
    {
        $this->language = new Language();
    }

    /**
     * @covers ::getLanguageCode
     * @covers ::setLanguageCode
     * @covers ::__toString
     */
    public function testLanguageCode()
    {
        $this->language->setLanguageCode('en');
        $this->assertEquals('en', $this->language->getLanguageCode());
        $this->assertEquals('en', (string) $this->language);
    }

    /**
     * @covers ::getName
     * @covers ::setName
     */
    public function testName()
    {
        $this->language->setName('English');
        $this->assertEquals('English', $this->language->getName());
    }

    /**
     * @covers ::getLocale
     * @covers ::setLocale
     */
    public function testLocale()
    {
        $this->language->setLocale('en');
        $this->assertEquals('en', $this->language->getLocale());
    }
}
