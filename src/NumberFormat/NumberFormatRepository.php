<?php

namespace CommerceGuys\Intl\NumberFormat;

use CommerceGuys\Intl\Locale;
use CommerceGuys\Intl\RepositoryLocaleTrait;

/**
 * Repository for number formats based on JSON definitions.
 */
class NumberFormatRepository implements NumberFormatRepositoryInterface
{
    use RepositoryLocaleTrait;

    /**
     * The path where the definitions are stored.
     *
     * @var string
     */
    protected $definitionPath;

    /**
     * Number formats.
     *
     * @var array
     */
    protected $numberFormats = [];

    /**
     * The available locales.
     *
     * @var array
     */
    protected $availableLocales = [
        'af', 'agq', 'ak', 'am', 'ar', 'ar-DZ', 'ar-EH', 'ar-LY', 'ar-MA',
        'ar-TN', 'ast', 'az', 'az-Cyrl', 'bas', 'be', 'bez', 'bg', 'bm',
        'bn', 'bo', 'br', 'brx', 'bs', 'bs-Cyrl', 'ca', 'ca-ES-VALENCIA',
        'ce', 'cgg', 'ckb', 'cs', 'cu', 'cy', 'da', 'de', 'de-AT', 'de-CH',
        'de-LI', 'dje', 'dsb', 'dyo', 'dz', 'ee', 'el', 'en', 'en-150',
        'en-AT', 'en-BE', 'en-CH', 'en-DE', 'en-DK', 'en-FI', 'en-IN',
        'en-NL', 'en-SE', 'en-SI', 'en-ZA', 'eo', 'es', 'es-419', 'es-AR',
        'es-BO', 'es-CL', 'es-CO', 'es-CR', 'es-DO', 'es-EC', 'es-GQ', 'es-PY',
        'es-UY', 'es-VE', 'et', 'eu', 'fa', 'fa-AF', 'ff', 'fi', 'fil', 'fo',
        'fr', 'fr-CH', 'fr-LU', 'fr-MA', 'fur', 'fy', 'ga', 'gd', 'gl', 'gsw',
        'gu', 'ha', 'haw', 'he', 'hi', 'hr', 'hsb', 'hu', 'hy', 'id', 'ig',
        'is', 'it', 'it-CH', 'ja', 'ka', 'kab', 'kea', 'khq', 'kk', 'km', 'kn',
        'ko', 'kok', 'ks', 'ksf', 'ksh', 'ky', 'lb', 'lg', 'lkt', 'lo', 'lrc',
        'lt', 'lu', 'luo', 'luy', 'lv', 'mas', 'mfe', 'mg', 'mgh', 'mk', 'ml',
        'mn', 'mr', 'ms', 'ms-BN', 'mt', 'mua', 'my', 'mzn', 'naq', 'nb',
        'nds', 'ne', 'nl', 'nl-BE', 'nn', 'nyn', 'om', 'or', 'pa', 'pa-Arab',
        'pl', 'prg', 'pt', 'pt-PT', 'qu', 'qu-BO', 'rm', 'rn', 'ro', 'rof',
        'ru', 'rw', 'sd', 'se', 'seh', 'ses', 'sg', 'si', 'sk', 'sl', 'smn',
        'so', 'sq', 'sr', 'sr-Latn', 'sv', 'sw', 'sw-CD', 'ta', 'ta-MY',
        'ta-SG', 'te', 'tg', 'th', 'ti', 'tk', 'to', 'tr', 'tt', 'twq',
        'tzm', 'ug', 'uk', 'ur', 'ur-IN', 'uz', 'uz-Arab', 'uz-Cyrl', 'vi',
        'vo', 'wae', 'wo', 'yav', 'yi', 'yo', 'yue-Hans', 'yue-Hant', 'zh',
        'zh-Hant', 'zu',
    ];

    /**
     * Creates a NumberFormatRepository instance.
     *
     * @param string $definitionPath The path to the number format definitions.
     *                               Defaults to 'resources/number_format'.
     */
    public function __construct($definitionPath = null)
    {
        $this->definitionPath = $definitionPath ? $definitionPath : __DIR__ . '/../../resources/number_format/';
    }

    /**
     * {@inheritdoc}
     */
    public function get($locale, $fallbackLocale = null)
    {
        $locale = $locale ?: $this->getDefaultLocale();
        $fallbackLocale = $fallbackLocale ?: $this->getFallbackLocale();
        $locale = Locale::resolve($this->availableLocales, $locale, $fallbackLocale);
        if (!isset($this->numberFormats[$locale])) {
            $filename = $this->definitionPath . $locale . '.json';
            $definition = json_decode(file_get_contents($filename), true);
            $definition['locale'] = $locale;
            $this->numberFormats[$locale] = new NumberFormat($definition);
        }

        return $this->numberFormats[$locale];
    }
}
