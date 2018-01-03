<?php

namespace CommerceGuys\Intl\Language;

use CommerceGuys\Intl\Locale;
use CommerceGuys\Intl\RepositoryLocaleTrait;
use CommerceGuys\Intl\Exception\UnknownLanguageException;

/**
 * Manages languages based on JSON definitions.
 */
class LanguageRepository implements LanguageRepositoryInterface
{
    use RepositoryLocaleTrait;

    /**
     * The path where per-locale definitions are stored.
     *
     * @var string
     */
    protected $definitionPath;

    /**
     * Per-locale language definitions.
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
        'af', 'agq', 'ak', 'am', 'ar', 'ar-EG', 'ar-LY', 'ar-SA', 'asa', 'ast',
        'az', 'az-Cyrl', 'bas', 'be', 'bem', 'bez', 'bg', 'bm', 'bn', 'bn-IN',
        'br', 'brx', 'bs', 'bs-Cyrl', 'ca', 'ccp', 'ce', 'cgg', 'chr', 'ckb',
        'cs', 'cy', 'da', 'dav', 'de', 'de-AT', 'de-CH', 'de-LU', 'dje', 'dsb',
        'dyo', 'dz', 'ebu', 'ee', 'el', 'en', 'en-AU', 'en-CA', 'en-GB',
        'en-IN', 'eo', 'es', 'es-419', 'es-AR', 'es-BO', 'es-CL', 'es-CO',
        'es-CR', 'es-DO', 'es-EC', 'es-GT', 'es-HN', 'es-MX', 'es-NI', 'es-PA',
        'es-PE', 'es-PR', 'es-PY', 'es-SV', 'es-US', 'es-VE', 'et', 'eu',
        'ewo', 'fa', 'fa-AF', 'ff', 'fi', 'fil', 'fo', 'fr', 'fr-BE', 'fr-CA',
        'fr-CH', 'fur', 'fy', 'ga', 'gd', 'gl', 'gsw', 'gu', 'guz', 'ha', 'he',
        'hi', 'hr', 'hsb', 'hu', 'hy', 'id', 'ig', 'is', 'it', 'ja', 'jmc',
        'ka', 'kab', 'kam', 'kde', 'kea', 'khq', 'ki', 'kk', 'kln', 'km', 'kn',
        'ko', 'kok', 'ks', 'ksb', 'ksf', 'ksh', 'ky', 'lag', 'lb', 'lg', 'lkt',
        'ln', 'lo', 'lrc', 'lt', 'lu', 'luo', 'luy', 'lv', 'mas', 'mer', 'mfe',
        'mg', 'mgh', 'mk', 'ml', 'mn', 'mr', 'ms', 'mt', 'mua', 'my', 'mzn',
        'naq', 'nb', 'nd', 'ne', 'nl', 'nmg', 'nn', 'nus', 'nyn', 'om', 'or',
        'os', 'pa', 'pl', 'ps', 'pt', 'pt-PT', 'qu', 'rm', 'rn', 'ro', 'ro-MD',
        'rof', 'ru', 'rw', 'rwk', 'sah', 'saq', 'sbp', 'sd', 'se', 'se-FI',
        'seh', 'ses', 'sg', 'shi', 'shi-Latn', 'si', 'sk', 'sl', 'smn', 'sn',
        'so', 'sq', 'sr', 'sr-Cyrl-BA', 'sr-Cyrl-ME', 'sr-Cyrl-XK', 'sr-Latn',
        'sr-Latn-BA', 'sr-Latn-ME', 'sr-Latn-XK', 'sv', 'sv-FI', 'sw', 'sw-CD',
        'sw-KE', 'ta', 'te', 'teo', 'tg', 'th', 'ti', 'tk', 'to', 'tr', 'tt',
        'twq', 'tzm', 'ug', 'uk', 'ur', 'ur-IN', 'uz', 'uz-Cyrl', 'vai',
        'vai-Latn', 'vi', 'vun', 'wae', 'wo', 'xog', 'yav', 'yi', 'yo',
        'yo-BJ', 'yue-Hans', 'yue-Hant', 'zgh', 'zh', 'zh-Hant',
        'zh-Hant-HK', 'zu',
    ];

    /**
     * Creates a LanguageRepository instance.
     *
     * @param string $definitionPath The path to the currency definitions.
     *                               Defaults to 'resources/language'.
     */
    public function __construct($definitionPath = null)
    {
        $this->definitionPath = $definitionPath ? $definitionPath : __DIR__ . '/../../resources/language/';
    }

    /**
     * {@inheritdoc}
     */
    public function get($languageCode, $locale = null, $fallbackLocale = null)
    {
        $locale = $locale ?: $this->getDefaultLocale();
        $fallbackLocale = $fallbackLocale ?: $this->getFallbackLocale();
        $locale = Locale::resolve($this->availableLocales, $locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        if (!isset($definitions[$languageCode])) {
            throw new UnknownLanguageException($languageCode);
        }

        return $this->createLanguageFromDefinition($languageCode, $definitions[$languageCode], $locale);
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
        $languages = [];
        foreach ($definitions as $languageCode => $definition) {
            $languages[$languageCode] = $this->createLanguageFromDefinition($languageCode, $definition, $locale);
        }

        return $languages;
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
        foreach ($definitions as $languageCode => $definition) {
            $list[$languageCode] = $definition['name'];
        }

        return $list;
    }

    /**
     * Loads the language definitions for the provided locale.
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
        }

        return $this->definitions[$locale];
    }

    /**
     * Creates a language object from the provided definition.
     *
     * @param string $languageCode The language code.
     * @param array  $definition   The language definition.
     * @param string $locale       The locale of the language definition.
     *
     * @return Language
     */
    protected function createLanguageFromDefinition($languageCode, array $definition, $locale)
    {
        $language = new Language();
        $setValues = \Closure::bind(function ($languageCode, $definition, $locale) {
            $this->languageCode = $languageCode;
            $this->name = $definition['name'];
            $this->locale = $locale;
        }, $language, '\CommerceGuys\Intl\Language\Language');
        $setValues($languageCode, $definition, $locale);

        return $language;
    }
}
