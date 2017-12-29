<?php

namespace CommerceGuys\Intl;

final class Locale
{
    /**
     * Common locale aliases.
     *
     * @var array
     */
    protected static $aliases = [
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
     * Canonicalizes the given locale.
     *
     * @param string $locale The locale.
     *
     * @return string The canonicalized locale.
     */
    public static function canonicalize($locale)
    {
        if (empty($locale)) {
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
     * Gets locale candidates.
     *
     * For example, "bs-Cyrl-BA" has the following candidates:
     * 1) bs-Cyrl-BA
     * 2) bs-Cyrl
     * 3) bs
     *
     * The locale is de-aliased, e.g. the candidates for "sh" are:
     * 1) sr-Latn
     * 2) sr
     *
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return array An array of all variants of a locale.
     */
    public static function getCandidates($locale, $fallbackLocale = null)
    {
        $candidates = [];
        $localeParts = explode('-', self::resolveAlias($locale));
        while (!empty($localeParts)) {
            $candidates[] = implode('-', $localeParts);
            array_pop($localeParts);
        }
        if (isset($fallbackLocale)) {
            $candidates[] = $fallbackLocale;
        }

        return $candidates;
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
    public static function resolveAlias($locale)
    {
        if (!empty($locale) && isset(self::$aliases[$locale])) {
            $locale = self::$aliases[$locale];
        }

        return $locale;
    }
}
