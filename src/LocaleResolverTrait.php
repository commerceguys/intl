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
     * Common locale aliases.
     *
     * @var array
     */
    protected $localeAliases = [
        'az-AZ' => 'az-Latn-AZ',
        'bs-BA' => 'bs-Latn-BA',
        'ha-GH' => 'ha-Latn-GH',
        'ha-NE' => 'ha-Latn-NE',
        'ha-NG' => 'ha-Latn-NG',
        'in' => 'id',
        'in-ID' => 'id-ID',
        'iw' => 'he',
        'iw-IL' => 'he-IL',
        'kk-KZ' => 'kk-Cyrl-KZ',
        'ks-IN' => 'ks-Arab-IN',
        'ky-KG' => 'ky-Cyrl-KG',
        'mn-MN' => 'mn-Cyrl-MN',
        'mo' => 'ro-MD',
        'ms-BN' => 'ms-Latn-BN',
        'ms-MY' => 'ms-Latn-MY',
        'ms-SG' => 'ms-Latn-SG',
        'no' => 'nb',
        'no-NO' => 'nb-NO',
        'no-NO-NY' => 'nn-NO',
        'pa-IN' => 'pa-Guru-IN',
        'pa-PK' => 'pa-Arab-PK',
        'sh' => 'sr-Latn',
        'sh-BA' => 'sr-Latn-BA',
        'sh-CS' => 'sr-Latn-RS',
        'sh-YU' => 'sr-Latn-RS',
        'shi-MA' => 'shi-Tfng-MA',
        'sr-BA' => 'sr-Cyrl-BA',
        'sr-ME' => 'sr-Latn-ME',
        'sr-RS' => 'sr-Cyrl-RS',
        'sr-XK' => 'sr-Cyrl-XK',
        'tl' => 'fil',
        'tl-PH' => 'fil-PH',
        'tzm-MA' => 'tzm-Latn-MA',
        'ug-CN' => 'ug-Arab-CN',
        'uz-AF' => 'uz-Arab-AF',
        'uz-UZ' => 'uz-Latn-UZ',
        'vai-LR' => 'vai-Vaii-LR',
        'zh-CN' => 'zh-Hans-CN',
        'zh-HK' => 'zh-Hant-HK',
        'zh-MO' => 'zh-Hant-MO',
        'zh-SG' => 'zh-Hans-SG',
        'zh-TW' => 'zh-Hant-TW',
    ];

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
        $locale = $this->canonicalizeLocale($locale);
        $locale = $this->resolveLocaleAlias($locale);
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
     * Resolves known locale aliases.
     *
     * For example, "zh-CN" is resolved to "zh-Hans-CN".
     *
     * @param string $locale The locale.
     *
     * @return string The locale.
     */
    protected function resolveLocaleAlias($locale = null)
    {
        if ($locale && isset($this->localeAliases[$locale])) {
            $locale = $this->localeAliases[$locale];
        }

        return $locale;
    }

    /**
     * Canonicalize the given locale.
     *
     * @param string $locale The locale.
     *
     * @return string The canonicalized locale.
     */
    protected function canonicalizeLocale($locale = null)
    {
        if (is_null($locale)) {
            return $locale;
        }

        $locale = str_replace('-', '_', strtolower($locale));
        $localeParts = explode('_', $locale);
        foreach ($localeParts as $index => $part) {
            if ($index === 0) {
                // The language code should stay lowercase.
                continue;
            }

            if (strlen($part) == 4) {
                // Script code.
                $localeParts[$index] = ucfirst($part);
            } else {
                // Country or variant code.
                $localeParts[$index] = strtoupper($part);
            }
        }

        return implode('-', $localeParts);
    }

    /**
     * Gets the default locale.
     *
     * @return string The default locale.
     */
    protected function getDefaultLocale()
    {
        return 'en';
    }

    /**
     * Gets all variants of a locale.
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
        $localeVariants = [];
        $localeParts = explode('-', $locale);
        while (!empty($localeParts)) {
            $localeVariants[] = implode('-', $localeParts);
            array_pop($localeParts);
        }

        return $localeVariants;
    }
}
