<?php

namespace CommerceGuys\Intl\Language;

/**
 * Language repository interface.
 */
interface LanguageRepositoryInterface
{
    /**
     * Gets a language matching the provided language code.
     *
     * @param string $languageCode The language code.
     * @param string $locale       The locale (i.e. fr-FR).
     *
     * @return Language
     */
    public function get($languageCode, $locale = null);

    /**
     * Gets all languages.
     *
     * @param string $locale The locale (i.e. fr-FR).
     *
     * @return Language[] An array of languages, keyed by language code.
     */
    public function getAll($locale = null);

    /**
     * Gets a list of languages.
     *
     * @param string $locale The locale (i.e. fr-FR).
     *
     * @return array An array of language names, keyed by language code.
     */
    public function getList($locale = null);
}
