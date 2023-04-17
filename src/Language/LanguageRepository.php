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
    protected string $defaultLocale;

    /**
     * The fallback locale.
     *
     * @var string
     */
    protected string $fallbackLocale;

    /**
     * The path where per-locale definitions are stored.
     *
     * @var string
     */
    protected string $definitionPath;

    /**
     * Per-locale language definitions.
     *
     * @var array
     */
    protected array $definitions = [];

    /**
     * The available locales.
     *
     * @var array
     */
    protected array $availableLocales = [
        'ab', 'af', 'an', 'ann', 'apc', 'ar', 'ar-EG', 'ar-LY', 'ar-SA', 'arn',
        'as', 'ast', 'az', 'az-Arab', 'az-Cyrl', 'ba', 'bal', 'bal-Latn', 'be',
        'bg', 'bgc', 'bgn', 'bho', 'blt', 'bn', 'bn-IN', 'bo', 'brx', 'bs',
        'bs-Cyrl', 'bss', 'byn', 'ca', 'cch', 'ce', 'cho', 'cic', 'co', 'cs',
        'cv', 'cy', 'da', 'de', 'de-AT', 'de-CH', 'doi', 'dz', 'el',
        'el-polyton', 'en', 'en-001', 'en-AU', 'en-CA', 'en-Dsrt', 'en-GB',
        'en-IN', 'es', 'es-419', 'es-AR', 'es-BO', 'es-CL', 'es-CO', 'es-CR',
        'es-DO', 'es-EC', 'es-GT', 'es-HN', 'es-MX', 'es-NI', 'es-PA', 'es-PE',
        'es-PR', 'es-PY', 'es-SV', 'es-US', 'es-VE', 'et', 'eu', 'fa', 'fa-AF',
        'fi', 'fil', 'fr', 'fr-BE', 'fr-CA', 'fr-CH', 'frr', 'ga', 'gaa', 'gd',
        'gl', 'gn', 'gsw', 'gu', 'he', 'hi', 'hi-Latn', 'hnj', 'hr', 'hu', 'hy',
        'id', 'io', 'is', 'it', 'ja', 'jbo', 'ka', 'kaj', 'kcg', 'ken', 'kk',
        'km', 'ko', 'kok', 'kpe', 'ks', 'ks-Deva', 'ku', 'ky', 'lb', 'lij',
        'lo', 'lt', 'lv', 'mai', 'mdf', 'mg', 'mk', 'ml', 'mn', 'mn-Mong-MN',
        'mni', 'mni-Mtei', 'moh', 'mr', 'ms', 'mt', 'mus', 'my', 'myv', 'ne',
        'nl', 'nn', 'no', 'nqo', 'nv', 'ny', 'osa', 'pa', 'pa-Arab', 'pap',
        'pis', 'pl', 'ps', 'ps-PK', 'pt', 'pt-PT', 'quc', 'raj', 'rhg', 'rif',
        'rn', 'ro', 'ro-MD', 'ru', 'rw', 'sat', 'sat-Deva', 'scn', 'sd',
        'sd-Deva', 'sdh', 'shn', 'si', 'sk', 'sl', 'sma', 'smj', 'sms', 'so',
        'sq', 'sr', 'sr-Cyrl-BA', 'sr-Cyrl-ME', 'sr-Cyrl-XK', 'sr-Latn',
        'sr-Latn-BA', 'sr-Latn-ME', 'sr-Latn-XK', 'ss', 'ssy', 'st', 'sv', 'sw',
        'sw-CD', 'sw-KE', 'syr', 'szl', 'ta', 'te', 'tg', 'th', 'tig', 'tk',
        'to', 'tok', 'tpi', 'tr', 'trv', 'trw', 'uk', 'ur', 'ur-IN', 'uz',
        'uz-Arab', 'uz-Cyrl', 'vec', 'vi', 'wa', 'wbp', 'yue', 'yue-Hans',
        'zh', 'zh-Hant', 'zh-Hant-HK'
    ];

    /**
     * Creates a LanguageRepository instance.
     *
     * @param string $defaultLocale  The default locale. Defaults to 'en'.
     * @param string $fallbackLocale The fallback locale. Defaults to 'en'.
     * @param string|null $definitionPath The path to the currency definitions.
     *                               Defaults to 'resources/language'.
     */
    public function __construct(string $defaultLocale = 'en', string $fallbackLocale = 'en', string $definitionPath = null)
    {
        $this->defaultLocale = $defaultLocale;
        $this->fallbackLocale = $fallbackLocale;
        $this->definitionPath = $definitionPath ?: __DIR__ . '/../../resources/language/';
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $languageCode, string $locale = null): Language
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
    public function getAll(string $locale = null): array
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
    public function getList(string $locale = null): array
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
    protected function loadDefinitions(string $locale): array
    {
        if (!isset($this->definitions[$locale])) {
            $filename = $this->definitionPath . $locale . '.json';
            $this->definitions[$locale] = json_decode(file_get_contents($filename), true);
        }

        return $this->definitions[$locale];
    }
}
