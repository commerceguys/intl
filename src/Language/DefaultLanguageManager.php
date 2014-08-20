<?php

namespace CommerceGuys\Intl\Language;

use CommerceGuys\Intl\LocaleResolverTrait;
use Symfony\Component\Yaml\Parser;

/**
 * Manages languages based on YAML definitions.
 */
class DefaultLanguageManager implements LanguageManagerInterface
{
    use LocaleResolverTrait;

    /**
     * Per-locale language definitions.
     *
     * @var array
     */
    protected $definitions = array();

    /**
     * The yaml parser.
     *
     * @var \Symfony\Component\Yaml\Parser
     */
    protected $parser;

    public function __construct()
    {
        $this->parser = new Parser();
        $this->definitionPath = __DIR__ . '/../../resources/language/';
    }

    /**
     * {@inheritdoc}
     */
    public function get($languageCode, $locale = 'en', $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        if (!isset($definitions[$languageCode])) {
            throw new UnknownLanguageException($languageCode);
        }

        return $this->createLanguageFromDefinition($definitions[$languageCode], $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll($locale = 'en', $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $languages = array();
        foreach ($definitions as $languageCode => $definition) {
            $languages[$languageCode] = $this->createLanguageFromDefinition($definition, $locale);
        }

        return $languages;
    }

    /**
     * Loads the language definitions for the provided locale.
     *
     * @param string $locale
     *   The desired locale.
     *
     * @return array
     */
    protected function loadDefinitions($locale)
    {
        if (!isset($this->definitions[$locale])) {
            $filename = $this->definitionPath . $locale . '.yml';
            $this->definitions[$locale] = $this->parser->parse(file_get_contents($filename));
        }

        return $this->definitions[$locale];
    }

    /**
     * Creates a language object from the provided definition.
     *
     * @param array $definition The language definition.
     * @param string $locale The locale of the language definition.
     *
     * @return \CommerceGuys\Intl\Language\Language
     */
    protected function createLanguageFromDefinition(array $definition, $locale)
    {
        $language = new Language();
        $language->setLanguageCode($definition['code']);
        $language->setName($definition['name']);
        $language->setLocale($locale);

        return $language;
    }
}
