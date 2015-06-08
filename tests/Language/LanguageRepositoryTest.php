<?php

namespace CommerceGuys\Intl\Tests\Language;

use CommerceGuys\Intl\Language\LanguageRepository;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Language\LanguageRepository
 */
class LanguageRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * English language definitions.
     *
     * @var array
     */
    protected $englishDefinitions = [
        'en' => [
            'name' => 'English',
        ],
        'fr' => [
            'name' => 'French',
        ],
    ];

    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        // Mock the existence of JSON definitions on the filesystem.
        $root = vfsStream::setup('resources');
        vfsStream::newFile('language/en.json')->at($root)->setContent(json_encode($this->englishDefinitions));

        // Instantiate the language repository and confirm that the definition path
        // was properly set.
        $languageRepository = new LanguageRepository('vfs://resources/language/');
        $definitionPath = $this->getObjectAttribute($languageRepository, 'definitionPath');
        $this->assertEquals('vfs://resources/language/', $definitionPath);

        return $languageRepository;
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     * @covers ::createLanguageFromDefinition
     *
     * @uses \CommerceGuys\Intl\Language\Language
     * @uses \CommerceGuys\Intl\LocaleResolverTrait
     * @depends testConstructor
     */
    public function testGet($languageRepository)
    {
        $language = $languageRepository->get('en');
        $this->assertInstanceOf('CommerceGuys\\Intl\\Language\\Language', $language);
        $this->assertEquals('en', $language->getLanguageCode());
        $this->assertEquals('English', $language->getName());
        $this->assertEquals('en', $language->getLocale());
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     *
     * @uses \CommerceGuys\Intl\LocaleResolverTrait
     * @expectedException \CommerceGuys\Intl\Exception\UnknownLanguageException
     * @depends testConstructor
     */
    public function testGetInvalidLanguage($languageRepository)
    {
        $languageRepository->get('de');
    }

    /**
     * @covers ::getAll
     * @covers ::loadDefinitions
     * @covers ::createLanguageFromDefinition
     *
     * @uses \CommerceGuys\Intl\Language\Language
     * @uses \CommerceGuys\Intl\LocaleResolverTrait
     * @depends testConstructor
     */
    public function testGetAll($languageRepository)
    {
        $languages = $languageRepository->getAll();
        $this->assertArrayHasKey('en', $languages);
        $this->assertArrayHasKey('fr', $languages);
        $this->assertEquals('en', $languages['en']->getLanguageCode());
        $this->assertEquals('fr', $languages['fr']->getLanguageCode());
    }

    /**
     * @covers ::getList
     * @covers ::loadDefinitions
     *
     * @uses \CommerceGuys\Intl\LocaleResolverTrait
     * @depends testConstructor
     */
    public function testGetList($languageRepository)
    {
        $list = $languageRepository->getList();
        $expectedList = ['en' => 'English', 'fr' => 'French'];
        $this->assertEquals($expectedList, $list);
    }
}
