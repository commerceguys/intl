<?php

namespace CommerceGuys\Intl\Tests\Language;

use CommerceGuys\Intl\Language\DefaultLanguageManager;
use Symfony\Component\Yaml\Dumper;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass CommerceGuys\Intl\Language\DefaultLanguageManager
 */
class DefaultLanguageManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * English language definitions.
     *
     * @var array
     */
    protected $englishDefinitions = array(
        'en' => array(
            'code' => 'en',
            'name' => 'English',
        ),
        'fr' => array(
            'code' => 'fr',
            'name' => 'French',
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
        vfsStream::newFile('language/en.yml')->at($root)->setContent($dumper->dump($this->englishDefinitions));

        // Instantiate the language manager.
        $languageManager = new DefaultLanguageManager('vfs://resources/language/');

        return $languageManager;
    }

    /**
     * @covers ::__construct
     * @covers ::get
     * @covers ::loadDefinitions
     * @covers ::createLanguageFromDefinition
     * @uses CommerceGuys\Intl\Language\Language::getLanguageCode
     * @uses CommerceGuys\Intl\Language\Language::setLanguageCode
     * @uses CommerceGuys\Intl\Language\Language::getName
     * @uses CommerceGuys\Intl\Language\Language::setName
     * @uses CommerceGuys\Intl\Language\Language::getLocale
     * @uses CommerceGuys\Intl\Language\Language::setLocale
     * @depends testConstructor
     */
    public function testGet($languageManager)
    {
        $language = $languageManager->get('en');
        $this->assertInstanceOf('CommerceGuys\\Intl\\Language\\Language', $language);
        $this->assertEquals($language->getLanguageCode(), 'en');
        $this->assertEquals($language->getName(), 'English');
        $this->assertEquals($language->getLocale(), 'en');
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     * @expectedException \CommerceGuys\Intl\Language\UnknownLanguageException
     * @depends testConstructor
     */
    public function testGetInvalidLanguage($languageManager)
    {
        $languageManager->get('de');
    }

    /**
     * @covers ::getAll
     * @covers ::loadDefinitions
     * @covers ::createLanguageFromDefinition
     * @uses CommerceGuys\Intl\Language\Language::getLanguageCode
     * @uses CommerceGuys\Intl\Language\Language::setLanguageCode
     * @uses CommerceGuys\Intl\Language\Language::setName
     * @uses CommerceGuys\Intl\Language\Language::setLocale
     * @depends testConstructor
     */
    public function testGetAll($languageManager)
    {
        $languages = $languageManager->getAll();
        $this->assertArrayHasKey('en', $languages);
        $this->assertArrayHasKey('fr', $languages);
        $this->assertEquals($languages['en']->getLanguageCode(), 'en');
        $this->assertEquals($languages['fr']->getLanguageCode(), 'fr');
    }
}
