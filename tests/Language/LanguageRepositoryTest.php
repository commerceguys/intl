<?php

namespace CommerceGuys\Intl\Tests\Language;

use CommerceGuys\Intl\Language\Language;
use CommerceGuys\Intl\Language\LanguageRepository;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Language\LanguageRepository
 */
class LanguageRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Language definitions.
     *
     * @var array
     */
    protected $definitions = [
        'en' => [
            'en' => 'English',
            'fr' => 'French',
            'fr-CA' => 'Canadian French',
        ],
        'es' => [
            'en' => 'inglés',
            'fr' => 'francés',
            'fr-CA' => 'francés canadiense',
        ],
        'de' => [
            'en' => 'Englisch',
            'fr' => 'Französisch',
            'fr-CA' => 'Französisch (Kanada)',
        ],
    ];

    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        // Mock the existence of JSON definitions on the filesystem.
        $root = vfsStream::setup('resources');
        foreach ($this->definitions as $locale => $data) {
            vfsStream::newFile('language/' . $locale . '.json')->at($root)->setContent(json_encode($data));
        }

        // Instantiate the language repository and confirm that the definition path
        // was properly set.
        $languageRepository = new LanguageRepository('de', 'en', 'vfs://resources/language/');
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
     * @uses \CommerceGuys\Intl\Locale
     * @depends testConstructor
     */
    public function testGet($languageRepository)
    {
        // Explicit locale.
        $language = $languageRepository->get('en', 'es');
        $this->assertInstanceOf(Language::class, $language);
        $this->assertEquals('en', $language->getLanguageCode());
        $this->assertEquals('inglés', $language->getName());
        $this->assertEquals('es', $language->getLocale());

        $language = $languageRepository->get('fr-CA', 'es');
        $this->assertInstanceOf(Language::class, $language);
        $this->assertEquals('fr-CA', $language->getLanguageCode());
        $this->assertEquals('francés canadiense', $language->getName());
        $this->assertEquals('es', $language->getLocale());

        // Default locale, non-canonical language code.
        $language = $languageRepository->get('EN');
        $this->assertInstanceOf(Language::class, $language);
        $this->assertEquals('en', $language->getLanguageCode());
        $this->assertEquals('Englisch', $language->getName());
        $this->assertEquals('de', $language->getLocale());

        $language = $languageRepository->get('FR_CA');
        $this->assertInstanceOf(Language::class, $language);
        $this->assertEquals('fr-CA', $language->getLanguageCode());
        $this->assertEquals('Französisch (Kanada)', $language->getName());
        $this->assertEquals('de', $language->getLocale());

        // Fallback locale.
        $language = $languageRepository->get('en', 'INVALID-LOCALE');
        $this->assertInstanceOf(Language::class, $language);
        $this->assertEquals('en', $language->getLanguageCode());
        $this->assertEquals('English', $language->getName());
        $this->assertEquals('en', $language->getLocale());

        $language = $languageRepository->get('fr-CA', 'INVALID-LOCALE');
        $this->assertInstanceOf(Language::class, $language);
        $this->assertEquals('fr-CA', $language->getLanguageCode());
        $this->assertEquals('Canadian French', $language->getName());
        $this->assertEquals('en', $language->getLocale());
    }

    /**
     * @covers ::get
     * @covers ::loadDefinitions
     *
     * @uses \CommerceGuys\Intl\Locale
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
     * @uses \CommerceGuys\Intl\Locale
     * @depends testConstructor
     */
    public function testGetAll($languageRepository)
    {
        // Explicit locale.
        $languages = $languageRepository->getAll('es');
        $this->assertArrayHasKey('en', $languages);
        $this->assertArrayHasKey('fr', $languages);
        $this->assertArrayHasKey('fr-CA', $languages);
        $this->assertEquals('inglés', $languages['en']->getName());
        $this->assertEquals('francés', $languages['fr']->getName());
        $this->assertEquals('francés canadiense', $languages['fr-CA']->getName());

        // Default locale.
        $languages = $languageRepository->getAll();
        $this->assertArrayHasKey('en', $languages);
        $this->assertArrayHasKey('fr', $languages);
        $this->assertArrayHasKey('fr-CA', $languages);
        $this->assertEquals('Englisch', $languages['en']->getName());
        $this->assertEquals('Französisch', $languages['fr']->getName());
        $this->assertEquals('Französisch (Kanada)', $languages['fr-CA']->getName());

        // Fallback locale.
        $languages = $languageRepository->getAll('INVALID-LOCALE');
        $this->assertArrayHasKey('en', $languages);
        $this->assertArrayHasKey('fr', $languages);
        $this->assertArrayHasKey('fr-CA', $languages);
        $this->assertEquals('English', $languages['en']->getName());
        $this->assertEquals('French', $languages['fr']->getName());
        $this->assertEquals('Canadian French', $languages['fr-CA']->getName());
    }

    /**
     * @covers ::getList
     * @covers ::loadDefinitions
     *
     * @uses \CommerceGuys\Intl\Locale
     * @depends testConstructor
     */
    public function testGetList($languageRepository)
    {
        // Explicit locale.
        $list = $languageRepository->getList('es');
        $this->assertEquals($this->definitions['es'], $list);

        // Default locale.
        $list = $languageRepository->getList();
        $this->assertEquals($this->definitions['de'], $list);

        // Fallback locale.
        $list = $languageRepository->getList('INVALID-LOCALE');
        $this->assertEquals($this->definitions['en'], $list);
    }
}
