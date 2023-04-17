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
        'af', 'am', 'ar', 'ar-EG', 'ar-LY', 'ar-SA', 'as', 'az', 'be', 'bg',
        'bn', 'bs', 'ca', 'cs', 'cy', 'da', 'de', 'de-AT', 'el', 'el-polyton',
        'en', 'en-AU', 'en-CA', 'en-IN', 'es', 'es-419', 'es-AR', 'es-BO',
        'es-CL', 'es-CO', 'es-CR', 'es-DO', 'es-EC', 'es-GT', 'es-HN', 'es-MX',
        'es-NI', 'es-PA', 'es-PE', 'es-PY', 'es-US', 'es-VE', 'et', 'eu', 'fa',
        'fa-AF', 'fi', 'fil', 'fr', 'fr-BE', 'fr-CA', 'fr-CH', 'ga', 'gd', 'gl',
        'gu', 'he', 'hi', 'hi-Latn', 'hr', 'hu', 'hy', 'id', 'ig', 'is', 'it',
        'ja', 'ka', 'kk', 'km', 'ko', 'kok', 'ky', 'lo', 'lt', 'lv', 'mk', 'mn',
        'mr', 'ms', 'my', 'ne', 'nl', 'nn', 'no', 'or', 'pa', 'pcm', 'pl', 'ps',
        'ps-PK', 'pt', 'pt-PT', 'ro', 'ro-MD', 'ru', 'si', 'sk', 'sl', 'so',
        'sq', 'sr', 'sr-Cyrl-BA', 'sr-Cyrl-ME', 'sr-Cyrl-XK', 'sr-Latn',
        'sr-Latn-BA', 'sr-Latn-ME', 'sr-Latn-XK', 'sv', 'sw', 'sw-CD',
        'sw-KE', 'ta', 'te', 'th', 'tk', 'tr', 'uk', 'ur', 'ur-IN', 'uz', 'vi',
        'yue', 'yue-Hans', 'zh', 'zh-Hant', 'zh-Hant-HK', 'zu'
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
