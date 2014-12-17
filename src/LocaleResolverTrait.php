<?php

namespace CommerceGuys\Intl;

use CommerceGuys\Intl\Exception\UnknownLocaleException;

trait LocaleResolverTrait
{
    /**
     * The path where per-locale definitions are stored.
     */
    protected $definitionPath;

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
        if (is_null($locale)) {
            // Use the default locale if none was provided.
            // @todo Provide a way to override this.
            $locale = 'en';
        }
        // Normalize the locale. Allows en_US to work the same as en-US, etc.
        $locale = str_replace('_', '-', $locale);
        // List all possible variants (i.e. en-US gives "en-US" and "en").
        $localeVariants = $this->getLocaleVariants($locale);
        // A fallback locale was provided, add it to the end of the chain.
        if (isset($fallbackLocale)) {
            $localeVariants[] = $fallbackLocale;
        }

        // Try to resolve a locale by finding a matching definition file.
        $resolvedLocale = null;
        foreach ($localeVariants as $localeVariant) {
            $path = $this->definitionPath . $localeVariant . '.json';
            if (file_exists($path)) {
                $resolvedLocale = $localeVariant;
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
     * Returns all variants of a locale.
     *
     * For example, "bs-Cyrl-BA" has the following variants:
     * 1) bs-Cyrl-BA
     * 2) bs-Cyrl
     * 3) bs
     *
     * @param string $locale The locale (i.e. fr-FR).
     *
     * @return array An array of all variants of a locale.
     */
    protected function getLocaleVariants($locale)
    {
        $localeVariants = array();
        $localeParts = explode('-', $locale);
        while (!empty($localeParts)) {
            $localeVariants[] = implode('-', $localeParts);
            array_pop($localeParts);
        }

        return $localeVariants;
    }
}
