<?php

namespace CommerceGuys\Intl;

trait RepositoryLocaleTrait
{
    /**
     * The default locale.
     *
     * @var string
     */
    protected $defaultLocale = 'en';

    /**
     * The fallback locale.
     *
     * @var string
     */
    protected $fallbackLocale = null;

    /**
     * Gets the default locale.
     *
     * @return string The default locale.
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * Sets the default locale.
     *
     * @param string $locale The default locale.
     *
     * @return void
     */
    public function setDefaultLocale($locale)
    {
        $this->defaultLocale = $locale;
    }

    /**
     * Gets the fallback locale.
     *
     * @return string The fallback locale.
     */
    public function getFallbackLocale()
    {
        return $this->fallbackLocale;
    }

    /**
     * Sets the fallback locale.
     *
     * @param string $locale The fallback locale.
     *
     * @return void
     */
    public function setFallbackLocale($locale)
    {
        $this->fallbackLocale = $locale;
    }
}
