<?php

namespace CommerceGuys\Intl\Country;

use CommerceGuys\Intl\Locale;
use CommerceGuys\Intl\RepositoryLocaleTrait;
use CommerceGuys\Intl\Exception\UnknownCountryException;

/**
 * Manages countries based on JSON definitions.
 */
class CountryRepository implements CountryRepositoryInterface
{
    use RepositoryLocaleTrait;

    /**
     * The path where per-locale definitions are stored.
     *
     * @var string
     */
    protected $definitionPath;

    /**
     * Base country definitions.
     *
     * Contains data common to all locales, such as the country numeric,
     * three-letter, currency codes.
     *
     * @var array
     */
    protected $baseDefinitions = [];

    /**
     * Per-locale country definitions.
     *
     * @var array
     */
    protected $definitions = [];

    /**
     * The available locales.
     *
     * @var array
     */
    protected $availableLocales = [
        'af', 'agq', 'ak', 'am', 'ar', 'ar-LY', 'ar-SA', 'as', 'asa', 'ast',
        'az', 'az-Cyrl', 'bas', 'be', 'bez', 'bg', 'bm', 'bn', 'bn-IN', 'br',
        'brx', 'bs', 'bs-Cyrl', 'ca', 'ccp', 'ce', 'cgg', 'chr', 'ckb', 'cs',
        'cy', 'da', 'dav', 'de', 'de-AT', 'de-CH', 'dje', 'dsb', 'dyo', 'dz',
        'ebu', 'ee', 'el', 'en', 'en-GB', 'eo', 'es', 'es-419', 'es-AR',
        'es-BO', 'es-CL', 'es-CO', 'es-CR', 'es-DO', 'es-EC', 'es-GT', 'es-HN',
        'es-MX', 'es-NI', 'es-PA', 'es-PE', 'es-PR', 'es-PY', 'es-SV', 'es-US',
        'es-VE', 'et', 'eu', 'ewo', 'fa', 'fa-AF', 'ff', 'fi', 'fil', 'fo',
        'fr', 'fr-BE', 'fr-CA', 'fur', 'fy', 'ga', 'gd', 'gl', 'gsw', 'gu',
        'guz', 'ha', 'he', 'hi', 'hr', 'hsb', 'hu', 'hy', 'id', 'is', 'it',
        'ja', 'jgo', 'jmc', 'ka', 'kab', 'kam', 'kde', 'kea', 'khq', 'ki',
        'kk', 'kln', 'km', 'kn', 'ko', 'ko-KP', 'kok', 'ks', 'ksb', 'ksf',
        'ksh', 'ky', 'lag', 'lb', 'lg', 'ln', 'lo', 'lt', 'lu', 'luo', 'luy',
        'lv', 'mas', 'mer', 'mfe', 'mg', 'mgh', 'mk', 'ml', 'mn', 'mr', 'ms',
        'mt', 'mua', 'my', 'mzn', 'naq', 'nb', 'nd', 'ne', 'nl', 'nmg', 'nn',
        'nus', 'nyn', 'or', 'pa', 'pl', 'ps', 'pt', 'pt-PT', 'qu', 'rm', 'rn',
        'ro', 'ro-MD', 'rof', 'ru', 'ru-UA', 'rwk', 'saq', 'sbp', 'sd', 'se',
        'se-FI', 'seh', 'ses', 'sg', 'shi', 'shi-Latn', 'si', 'sk', 'sl',
        'smn', 'sn', 'so', 'sq', 'sr', 'sr-Cyrl-BA', 'sr-Cyrl-ME',
        'sr-Cyrl-XK', 'sr-Latn', 'sr-Latn-BA', 'sr-Latn-ME', 'sr-Latn-XK',
        'sv', 'sw', 'sw-CD', 'sw-KE', 'ta', 'te', 'teo', 'tg', 'th', 'ti',
        'tk', 'to', 'tr', 'tt', 'twq', 'tzm', 'ug', 'uk', 'ur', 'ur-IN', 'uz',
        'uz-Cyrl', 'vai', 'vai-Latn', 'vi', 'vun', 'wae', 'wo', 'xog', 'yav',
        'yi', 'yo', 'yo-BJ', 'yue-Hans', 'yue-Hant', 'zgh', 'zh', 'zh-Hant',
        'zh-Hant-HK', 'zu',
    ];

    /**
     * Creates a CountryRepository instance.
     *
     * @param string $definitionPath The path to the country definitions.
     *                               Defaults to 'resources/country'.
     */
    public function __construct($definitionPath = null)
    {
        $this->definitionPath = $definitionPath ? $definitionPath : __DIR__ . '/../../resources/country/';
    }

    /**
     * {@inheritdoc}
     */
    public function get($countryCode, $locale = null, $fallbackLocale = null)
    {
        $locale = $locale ?: $this->getDefaultLocale();
        $fallbackLocale = $fallbackLocale ?: $this->getFallbackLocale();
        $locale = Locale::resolve($this->availableLocales, $locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        if (!isset($definitions[$countryCode])) {
            throw new UnknownCountryException($countryCode);
        }
        $definition = $definitions[$countryCode];
        $definition['country_code'] = $countryCode;
        $definition['locale'] = $locale;

        return new Country($definition);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll($locale = null, $fallbackLocale = null)
    {
        $locale = $locale ?: $this->getDefaultLocale();
        $fallbackLocale = $fallbackLocale ?: $this->getFallbackLocale();
        $locale = Locale::resolve($this->availableLocales, $locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $countries = [];
        foreach ($definitions as $countryCode => $definition) {
            $definition['country_code'] = $countryCode;
            $definition['locale'] = $locale;
            $countries[$countryCode] = new Country($definition);
        }

        return $countries;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($locale = null, $fallbackLocale = null)
    {
        $locale = $locale ?: $this->getDefaultLocale();
        $fallbackLocale = $fallbackLocale ?: $this->getFallbackLocale();
        $locale = Locale::resolve($this->availableLocales, $locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $list = [];
        foreach ($definitions as $countryCode => $definition) {
            $list[$countryCode] = $definition['name'];
        }

        return $list;
    }

    /**
     * Loads the country definitions for the provided locale.
     *
     * @param string $locale The desired locale.
     *
     * @return array
     */
    protected function loadDefinitions($locale)
    {
        if (!isset($this->definitions[$locale])) {
            $filename = $this->definitionPath . $locale . '.json';
            $this->definitions[$locale] = json_decode(file_get_contents($filename), true);

            // Make sure the base definitions have been loaded.
            if (empty($this->baseDefinitions)) {
                $this->baseDefinitions = json_decode(file_get_contents($this->definitionPath . 'base.json'), true);
            }
            // Merge-in base definitions.
            foreach ($this->definitions[$locale] as $countryCode => $definition) {
                $this->definitions[$locale][$countryCode] += $this->baseDefinitions[$countryCode];
            }
        }

        return $this->definitions[$locale];
    }
}
