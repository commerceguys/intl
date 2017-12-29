<?php

namespace CommerceGuys\Intl;

use CommerceGuys\Intl\Exception\UnknownLocaleException;

trait LocaleResolverTrait
{
    /**
     * The path where per-locale definitions are stored.
     *
     * @var string
     */
    protected $definitionPath;

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
     * Determines which locale should be used for loading definitions.
     *
     * If the "bs-Cyrl-BA" locale is requested, with an "en" fallback,
     * the system will try to find the definitions for:
     * 1) bs-Cyrl-BA
     * 2) bs-Cyrl
     * 3) bs
     * 4) en
     * The first locale for which a definition file is found, wins.
     * Otherwise, an exception is thrown.
     *
     * @param string $locale         The desired locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return string
     *
     * @throws UnknownLocaleException
     */
    protected function resolveLocale($locale = null, $fallbackLocale = null)
    {
        $locale = $locale ?: $this->getDefaultLocale();
        $locale = Locale::canonicalize($locale);
        $fallbackLocale = $fallbackLocale ?: $this->getFallbackLocale();
        $candidates = Locale::getCandidates($locale, $fallbackLocale);
        // Try to resolve a locale by finding a matching definition file.
        $resolvedLocale = null;
        foreach ($candidates as $candidate) {
            $path = $this->definitionPath . $candidate . '.json';
            if (file_exists($path)) {
                $resolvedLocale = $candidate;
                break;
            }
        }
        // No locale could be resolved, stop here.
        if (!$resolvedLocale) {
            throw new UnknownLocaleException($locale);
        }

        return $resolvedLocale;
    }

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
     * @return void
     */
    public function setFallbackLocale($locale)
    {
        $this->fallbackLocale = $locale;
    }
}
