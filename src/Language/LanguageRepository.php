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
        'ab', 'af', 'agq', 'ak', 'am', 'an', 'ann', 'apc', 'ar', 'ar-EG',
        'ar-LY', 'ar-SA', 'arn', 'as', 'asa', 'ast', 'az', 'az-Arab', 'az-Cyrl',
        'ba', 'bal', 'bal-Latn', 'bas', 'be', 'bem', 'bez', 'bg', 'bgc', 'bgn',
        'bho', 'blo', 'blt', 'bn', 'bn-IN', 'bo', 'br', 'brx', 'bs', 'bs-Cyrl',
        'bss', 'ca', 'cch', 'ccp', 'ce', 'ceb', 'cgg', 'cho', 'chr', 'cic',
        'ckb', 'co', 'cs', 'csw', 'cv', 'cy', 'da', 'dav', 'de', 'de-AT',
        'de-CH', 'doi', 'dsb', 'dua', 'dz', 'ebu', 'ee', 'el', 'el-polyton',
        'en', 'en-001', 'en-AU', 'en-CA', 'en-CZ', 'en-Dsrt', 'en-ES', 'en-FR',
        'en-GB', 'en-GS', 'en-HU', 'en-IN', 'en-IT', 'en-NO', 'en-PL', 'en-PT',
        'en-RO', 'en-SK', 'es', 'es-419', 'es-AR', 'es-BO', 'es-CL', 'es-CO',
        'es-CR', 'es-DO', 'es-EC', 'es-GT', 'es-HN', 'es-MX', 'es-NI', 'es-PA',
        'es-PE', 'es-PR', 'es-PY', 'es-SV', 'es-US', 'es-VE', 'et', 'eu', 'ewo',
        'fa', 'fa-AF', 'fi', 'fil', 'fo', 'fr', 'fr-BE', 'fr-CA', 'fr-CH',
        'frr', 'fur', 'fy', 'ga', 'gaa', 'gd', 'gl', 'gn', 'gsw', 'gu', 'guz',
        'haw', 'he', 'hi', 'hi-Latn', 'hnj', 'hr', 'hsb', 'ht', 'hu', 'hy',
        'id', 'ie', 'ig', 'ii', 'io', 'is', 'it', 'ja', 'jbo', 'jgo', 'jmc',
        'ka', 'kaa', 'kab', 'kaj', 'kam', 'kcg', 'kde', 'kea', 'ken', 'kgp',
        'khq', 'ki', 'kk', 'kk-Arab', 'kkj', 'kl', 'kln', 'km', 'ko', 'kok',
        'kok-Latn', 'kpe', 'ks', 'ks-Deva', 'ksb', 'ksf', 'ksh', 'ku', 'kw',
        'kxv', 'kxv-Deva', 'kxv-Orya', 'kxv-Telu', 'ky', 'lag', 'lb', 'lg',
        'lij', 'lkt', 'lld', 'lmo', 'ln', 'lo', 'lrc', 'lt', 'lu', 'luo',
        'luy', 'lv', 'mai', 'mas', 'mdf', 'mer', 'mfe', 'mg', 'mgh', 'mgo',
        'mi', 'mic', 'mk', 'mn', 'mn-Mong-MN', 'mni', 'mni-Mtei', 'moh', 'mr',
        'ms', 'mt', 'mua', 'mus', 'my', 'myv', 'mzn', 'naq', 'nd', 'nds', 'ne',
        'nl', 'nmg', 'nn', 'nnh', 'no', 'nqo', 'nso', 'nus', 'nv', 'ny', 'nyn',
        'oc', 'om', 'or', 'os', 'osa', 'pa', 'pa-Arab', 'pap', 'pcm', 'pis',
        'pl', 'ps', 'ps-PK', 'pt', 'pt-PT', 'qu', 'quc', 'raj', 'rhg', 'rif',
        'rm', 'rn', 'ro', 'ro-MD', 'rof', 'ru', 'rw', 'rwk', 'sa', 'sah', 'saq',
        'sbp', 'sc', 'scn', 'sdh', 'se', 'se-FI', 'seh', 'ses', 'sg', 'shn',
        'si', 'sk', 'skr', 'sl', 'sma', 'smj', 'smn', 'sms', 'sn', 'so', 'sq',
        'sr', 'sr-Cyrl-BA', 'sr-Cyrl-ME', 'sr-Cyrl-XK', 'sr-Latn', 'sr-Latn-BA',
        'sr-Latn-ME', 'sr-Latn-XK', 'ss', 'ssy', 'st', 'su', 'sv', 'sw',
        'sw-CD', 'sw-KE', 'syr', 'szl', 'ta', 'te', 'teo', 'tg', 'th', 'ti',
        'ti-ER', 'tig', 'tk', 'tn', 'tok', 'tpi', 'tr', 'trv', 'trw', 'tt',
        'twq', 'tzm', 'ug', 'uk', 'ur', 'ur-IN', 'uz', 'uz-Arab', 'uz-Cyrl',
        'vec', 'vi', 'vmw', 'vun', 'wa', 'wae', 'wbp', 'xh', 'xnr', 'xog',
        'yav', 'yi', 'yrl', 'yrl-CO', 'yrl-VE', 'yue', 'yue-Hans', 'za',
        'zgh', 'zh', 'zh-Hant', 'zh-Hant-HK', 'zu',
    ];

    /**
     * Creates a LanguageRepository instance.
     *
     * @param string $defaultLocale  The default locale. Defaults to 'en'.
     * @param string $fallbackLocale The fallback locale. Defaults to 'en'.
     * @param string|null $definitionPath The path to the currency definitions.
     *                               Defaults to 'resources/language'.
     */
    public function __construct(string $defaultLocale = 'en', string $fallbackLocale = 'en', ?string $definitionPath = null)
    {
        $this->defaultLocale = $defaultLocale;
        $this->fallbackLocale = $fallbackLocale;
        $this->definitionPath = $definitionPath ?: __DIR__ . '/../../resources/language/';
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $languageCode, ?string $locale = null): Language
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
    public function getAll(?string $locale = null): array
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
    public function getList(?string $locale = null): array
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
