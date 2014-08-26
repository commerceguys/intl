<?php

namespace CommerceGuys\Intl\Tests\Language;

use CommerceGuys\Intl\Language\Language;

/**
 * @coversDefaultClass CommerceGuys\Intl\Language\Language
 */
class LanguageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \CommerceGuys\Intl\Language\Language
     */
    protected $language;

    public function setUp()
    {
        $this->language = new Language;
    }

    /**
     * @covers ::getLanguageCode
     * @covers ::setLanguageCode
     * @covers ::__toString
     */
    public function testLanguageCode()
    {
        $this->language->setLanguageCode('en');
        $this->assertEquals($this->language->getLanguageCode(), 'en');
        $this->assertEquals((string) $this->language, 'en');
    }

    /**
     * @covers ::getName
     * @covers ::setName
     */
    public function testName()
    {
        $this->language->setName('English');
        $this->assertEquals($this->language->getName(), 'English');
    }

    /**
     * @covers ::getLocale
     * @covers ::setLocale
     */
    public function testLocale()
    {
        $this->language->setLocale('en');
        $this->assertEquals($this->language->getLocale(), 'en');
    }
}
