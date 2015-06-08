<?php

namespace CommerceGuys\Intl\Language;

use CommerceGuys\Intl\LocaleResolverTrait;
use CommerceGuys\Intl\Exception\UnknownLanguageException;

/**
 * Manages languages based on JSON definitions.
 */
class LanguageRepository implements LanguageRepositoryInterface
{
    use LocaleResolverTrait;

    /**
     * Per-locale language definitions.
     *
     * @var array
     */
    protected $definitions = [];

    /**
     * Creates a LanguageRepository instance.
     *
     * @param string $definitionPath The path to the currency definitions.
     *                               Defaults to 'resources/language'.
     */
    public function __construct($definitionPath = null)
    {
        $this->definitionPath = $definitionPath ? $definitionPath : __DIR__ . '/../../resources/language/';
    }

    /**
     * {@inheritdoc}
     */
    public function get($languageCode, $locale = null, $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        if (!isset($definitions[$languageCode])) {
            throw new UnknownLanguageException($languageCode);
        }

        return $this->createLanguageFromDefinition($languageCode, $definitions[$languageCode], $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll($locale = null, $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $languages = [];
        foreach ($definitions as $languageCode => $definition) {
            $languages[$languageCode] = $this->createLanguageFromDefinition($languageCode, $definition, $locale);
        }

        return $languages;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($locale = null, $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $list = [];
        foreach ($definitions as $languageCode => $definition) {
            $list[$languageCode] = $definition['name'];
        }

        return $list;
    }

    /**
     * Loads the language definitions for the provided locale.
     *
     * @param string $locale The desired locale.
     *
     * @return array
     */
    protected function loadDefinitions($locale)
    {
        if (!isset($this->definitions[$locale])) {
            $filename = $this->definitionPath . $locale . '.json';
            $this->definitions[$locale] = json_decode(file_get_contents($filename), true);
        }

        return $this->definitions[$locale];
    }

    /**
     * Creates a language object from the provided definition.
     *
     * @param string $languageCode The language code.
     * @param array  $definition   The language definition.
     * @param string $locale       The locale of the language definition.
     *
     * @return Language
     */
    protected function createLanguageFromDefinition($languageCode, array $definition, $locale)
    {
        $language = new Language();
        $setValues = \Closure::bind(function ($languageCode, $definition, $locale) {
            $this->languageCode = $languageCode;
            $this->name = $definition['name'];
            $this->locale = $locale;
        }, $language, '\CommerceGuys\Intl\Language\Language');
        $setValues($languageCode, $definition, $locale);

        return $language;
    }
}
