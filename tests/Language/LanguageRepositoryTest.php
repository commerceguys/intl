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
     * Language definitions.
     *
     * @var array
     */
    protected $definitions = [
        'en' => [
            'en' => 'English',
            'fr' => 'French',
        ],
        'es' => [
            'en' => 'inglés',
            'fr' => 'francés',
        ],
        'de' => [
            'en' => 'Englisch',
            'fr' => 'Französisch',
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
        $this->assertInstanceOf('CommerceGuys\\Intl\\Language\\Language', $language);
        $this->assertEquals('en', $language->getLanguageCode());
        $this->assertEquals('inglés', $language->getName());
        $this->assertEquals('es', $language->getLocale());

        // Default locale, uppercase language code.
        $language = $languageRepository->get('EN');
        $this->assertInstanceOf('CommerceGuys\\Intl\\Language\\Language', $language);
        $this->assertEquals('en', $language->getLanguageCode());
        $this->assertEquals('Englisch', $language->getName());
        $this->assertEquals('de', $language->getLocale());

        // Fallback locale.
        $language = $languageRepository->get('en', 'INVALID-LOCALE');
        $this->assertInstanceOf('CommerceGuys\\Intl\\Language\\Language', $language);
        $this->assertEquals('en', $language->getLanguageCode());
        $this->assertEquals('English', $language->getName());
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
        $this->assertEquals('inglés', $languages['en']->getName());
        $this->assertEquals('francés', $languages['fr']->getName());

        // Default locale.
        $languages = $languageRepository->getAll();
        $this->assertArrayHasKey('en', $languages);
        $this->assertArrayHasKey('fr', $languages);
        $this->assertEquals('Englisch', $languages['en']->getName());
        $this->assertEquals('Französisch', $languages['fr']->getName());

        // Fallback locale.
        $languages = $languageRepository->getAll('INVALID-LOCALE');
        $this->assertArrayHasKey('en', $languages);
        $this->assertArrayHasKey('fr', $languages);
        $this->assertEquals('English', $languages['en']->getName());
        $this->assertEquals('French', $languages['fr']->getName());
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
        $this->assertEquals(['en' => 'inglés', 'fr' => 'francés'], $list);

        // Default locale.
        $list = $languageRepository->getList();
        $this->assertEquals(['en' => 'Englisch', 'fr' => 'Französisch'], $list);

        // Fallback locale.
        $list = $languageRepository->getList('INVALID-LOCALE');
        $this->assertEquals(['en' => 'English', 'fr' => 'French'], $list);
    }
}
