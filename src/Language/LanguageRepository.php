<?php

namespace CommerceGuys\Intl\Language;

use CommerceGuys\Intl\Locale;
use CommerceGuys\Intl\Exception\UnknownLanguageException;

/**
 * Manages languages based on JSON definitions.
 */
class LanguageRepository implements LanguageRepositoryInterface
{
    /**
     * The default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * The fallback locale.
     *
     * @var string
     */
    protected $fallbackLocale;

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
        'af', 'agq', 'ak', 'am', 'ar', 'ar-EG', 'ar-LY', 'ar-SA', 'as', 'asa',
        'ast', 'az', 'az-Cyrl', 'bas', 'be', 'bem', 'bez', 'bg', 'bm', 'bn',
        'bn-IN', 'br', 'brx', 'bs', 'bs-Cyrl', 'ca', 'ccp', 'ce', 'cgg', 'chr',
        'ckb', 'cs', 'cy', 'da', 'dav', 'de', 'de-AT', 'de-CH', 'de-LU', 'dje',
        'dsb', 'dyo', 'dz', 'ebu', 'ee', 'el', 'en', 'en-001', 'en-AU', 'en-CA',
        'en-IN', 'en-NZ', 'eo', 'es', 'es-419', 'es-AR', 'es-BO', 'es-CL',
        'es-CO', 'es-CR', 'es-DO', 'es-EC', 'es-GT', 'es-HN', 'es-MX', 'es-NI',
        'es-PA', 'es-PE', 'es-PR', 'es-PY', 'es-SV', 'es-US', 'es-VE', 'et',
        'eu', 'ewo', 'fa', 'fa-AF', 'ff', 'fi', 'fil', 'fo', 'fr', 'fr-BE',
        'fr-CA', 'fr-CH', 'fur', 'fy', 'ga', 'gd', 'gl', 'gsw', 'gu', 'guz',
        'ha', 'ha-NE', 'he', 'hi', 'hr', 'hsb', 'hu', 'hy', 'id', 'ig', 'is',
        'it', 'ja', 'jmc', 'jv', 'ka', 'kab', 'kam', 'kde', 'kea', 'khq', 'ki',
        'kk', 'kln', 'km', 'kn', 'ko', 'kok', 'ks', 'ksb', 'ksf', 'ksh', 'ku',
        'ky', 'lag', 'lb', 'lg', 'lkt', 'ln', 'lo', 'lrc', 'lt', 'lu', 'luo',
        'luy', 'lv', 'mas', 'mer', 'mfe', 'mg', 'mgh', 'mk', 'ml', 'mn', 'mr',
        'ms', 'mt', 'mua', 'my', 'mzn', 'naq', 'nb', 'nd', 'ne', 'nl', 'nmg',
        'nn', 'nus', 'nyn', 'om', 'or', 'os', 'pa', 'pl', 'ps', 'ps-PK', 'pt',
        'pt-PT', 'qu', 'rm', 'rn', 'ro', 'ro-MD', 'rof', 'ru', 'rw', 'rwk',
        'sah', 'saq', 'sbp', 'sd', 'se', 'se-FI', 'seh', 'ses', 'sg', 'shi',
        'shi-Latn', 'si', 'sk', 'sl', 'smn', 'sn', 'so', 'sq', 'sr',
        'sr-Cyrl-BA', 'sr-Cyrl-ME', 'sr-Cyrl-XK', 'sr-Latn', 'sr-Latn-BA',
        'sr-Latn-ME', 'sr-Latn-XK', 'sv', 'sw', 'sw-CD', 'sw-KE', 'ta', 'te',
        'teo', 'tg', 'th', 'ti', 'tk', 'to', 'tr', 'tt', 'twq', 'tzm', 'ug',
        'uk', 'ur', 'ur-IN', 'uz', 'uz-Cyrl', 'vai', 'vai-Latn', 'vi', 'vun',
        'wae', 'wo', 'xog', 'yav', 'yi', 'yo', 'yo-BJ', 'yue', 'yue-Hans',
        'zgh', 'zh', 'zh-Hant', 'zh-Hant-HK', 'zu',
    ];

    /**
     * Creates a LanguageRepository instance.
     *
     * @param string $defaultLocale  The default locale. Defaults to 'en'.
     * @param string $fallbackLocale The fallback locale. Defaults to 'en'.
     * @param string $definitionPath The path to the currency definitions.
     *                               Defaults to 'resources/language'.
     */
    public function __construct($defaultLocale = 'en', $fallbackLocale = 'en', $definitionPath = null)
    {
        $this->defaultLocale = $defaultLocale;
        $this->fallbackLocale = $fallbackLocale;
        $this->definitionPath = $definitionPath ? $definitionPath : __DIR__ . '/../../resources/language/';
    }

    /**
     * {@inheritdoc}
     */
    public function get($languageCode, $locale = null)
    {
        $locale = $locale ?: $this->defaultLocale;
        $locale = Locale::resolve($this->availableLocales, $locale, $this->fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $languageCode = Locale::canonicalize($languageCode);
        if (!isset($definitions[$languageCode])) {
            throw new UnknownLanguageException($languageCode);
        }
        $language =  new Language([
            'language_code' => $languageCode,
            'name' => $definitions[$languageCode],
            'locale' => $locale,
        ]);

        return $language;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll($locale = null)
    {
        $locale = $locale ?: $this->defaultLocale;
        $locale = Locale::resolve($this->availableLocales, $locale, $this->fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $languages = [];
        foreach ($definitions as $languageCode => $languageName) {
            $languages[$languageCode] = new Language([
                'language_code' => $languageCode,
                'name' => $languageName,
                'locale' => $locale,
            ]);
        }

        return $languages;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($locale = null)
    {
        $locale = $locale ?: $this->defaultLocale;
        $locale = Locale::resolve($this->availableLocales, $locale, $this->fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $list = [];
        foreach ($definitions as $languageCode => $languageName) {
            $list[$languageCode] = $languageName;
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
}
