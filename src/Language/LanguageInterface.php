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
     * Gets the language name.
     *
     * Note that certain locales have incomplete translations, in which
     * case the english version of the language name is used instead.
     *
     * @return string
     */
    public function getName();
}
