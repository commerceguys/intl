<?php

namespace CommerceGuys\Intl\Language;

/**
 * Language repository interface.
 */
interface LanguageRepositoryInterface
{
    /**
     * Returns a language instance matching the provided language code.
     *
     * @param string $languageCode   The language code.
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return LanguageInterface
     */
    public function get($languageCode, $locale = null, $fallbackLocale = null);

    /**
     * Returns all available language instances.
     *
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return array An array of language implementing the LanguageInterface,
     *               keyed by language code.
     */
    public function getAll($locale = null, $fallbackLocale = null);
}
