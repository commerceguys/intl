<?php

namespace CommerceGuys\Intl\Language;

class Language implements LanguageEntityInterface
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
     * The language locale (i.e. "en-US").
     *
     * @var string
     */
    protected $locale;

    /**
     * Returns the string representation of the Language.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLanguageCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
