<?php

namespace CommerceGuys\Intl\Language;

/**
 * Language manager interface.
 */
interface LanguageManagerInterface
{
    /**
     * Returns a language instance matching the provided language code.
     *
     * @param string $languageCode The language code.
     * @param string $locale The locale (i.e. fr_FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return \CommerceGuys\Intl\Language\LanguageInterface
     */
    public function get($languageCode, $locale = 'en', $fallbackLocale = null);

    /**
     * Returns all available language instances.
     *
     * @param string $locale The locale (i.e. fr_FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return array An array of language implementing the LanguageInterface,
     *               keyed by language code.
     */
    public function getAll($locale = 'en', $fallbackLocale = null);
}
