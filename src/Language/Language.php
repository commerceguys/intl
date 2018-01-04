<?php

namespace CommerceGuys\Intl\Language;

/**
 * Represents a language.
 */
final class Language
{
    /**
     * The two-letter language code.
     *
     * @var string
     */
    protected $languageCode;

    /**
     * The language name.
     *
     * @var string
     */
    protected $name;

    /**
     * The locale (i.e. "en-US").
     *
     * @var string
     */
    protected $locale;

    /**
     * Creates a new Language instance.
     *
     * @param array $definition The definition array.
     */
    public function __construct(array $definition)
    {
        foreach (['language_code', 'name', 'locale'] as $requiredProperty) {
            if (empty($definition[$requiredProperty])) {
                throw new \InvalidArgumentException(sprintf('Missing required property "%s".', $requiredProperty));
            }
        }

        $this->languageCode = $definition['language_code'];
        $this->name = $definition['name'];
        $this->locale = $definition['locale'];
    }

    /**
     * Returns the string representation of the Language.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->languageCode;
    }

    /**
     * Gets the two-letter language code.
     *
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * Gets the language name.
     *
     * This value is locale specific.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the locale.
     *
     * The language name is locale specific.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
