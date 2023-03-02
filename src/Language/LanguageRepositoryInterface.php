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
     * @param string|null $locale       The locale (i.e. fr-FR).
     *
     * @return Language
     *
     * @throws \CommerceGuys\Intl\Exception\UnknownLanguageException
     */
    public function get(string $languageCode, string $locale = null): Language;

    /**
     * Gets all languages.
     *
     * @param string|null $locale The locale (i.e. fr-FR).
     *
     * @return Language[] An array of languages, keyed by language code.
     */
    public function getAll(string $locale = null): array;

    /**
     * Gets a list of languages.
     *
     * @param string|null $locale The locale (i.e. fr-FR).
     *
     * @return array An array of language names, keyed by language code.
     */
    public function getList(string $locale = null): array;
}
