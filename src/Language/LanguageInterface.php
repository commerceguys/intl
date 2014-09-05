<?php

namespace CommerceGuys\Intl\Language;

interface LanguageInterface
{
    /**
     * Gets the two-letter language code.
     *
     * @return string
     */
    public function getLanguageCode();

    /**
     * Sets the two-letter language code.
     *
     * @param string $languageCode The two-letter language code.
     */
    public function setLanguageCode($languageCode);

    /**
     * Gets the language name.
     *
     * Note that certain locales have incomplete translations, in which
     * case the english version of the language name is used instead.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the language name.
     *
     * @param string $name The language name.
     */
    public function setName($name);
}
