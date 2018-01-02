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
        'ar-TN', 'ast', 'az', 'bas', 'be', 'bez', 'bg', 'bm', 'bn', 'bo',
        'br', 'brx', 'bs', 'bs-Cyrl', 'ca', 'ce', 'cgg', 'ckb', 'cs', 'cu',
        'cy', 'da', 'de', 'de-AT', 'de-CH', 'de-LI', 'dje', 'dsb', 'dyo', 'dz',
        'ee', 'el', 'en', 'en-AT', 'en-BE', 'en-CH', 'en-DE', 'en-DK', 'en-FI',
        'en-IN', 'en-NL', 'en-SE', 'en-SI', 'en-ZA', 'eo', 'es', 'es-AR',
        'es-BO', 'es-BR', 'es-BZ', 'es-CL', 'es-CO', 'es-CR', 'es-CU', 'es-DO',
        'es-EC', 'es-GQ', 'es-GT', 'es-HN', 'es-MX', 'es-NI', 'es-PA', 'es-PE',
        'es-PR', 'es-PY', 'es-SV', 'es-US', 'es-UY', 'es-VE', 'et', 'eu', 'fa',
        'fa-AF', 'ff', 'fi', 'fil', 'fo', 'fr', 'fr-CH', 'fr-LU', 'fr-MA',
        'fur', 'fy', 'ga', 'gd', 'gl', 'gsw', 'gu', 'ha', 'haw', 'he', 'hi',
        'hr', 'hsb', 'hu', 'hy', 'id', 'ig', 'is', 'it', 'it-CH', 'ja', 'ka',
        'kab', 'kea', 'khq', 'kk', 'km', 'kn', 'ko', 'kok', 'ks', 'ksf', 'ksh',
        'ky', 'lb', 'lg', 'lkt', 'lo', 'lrc', 'lt', 'lu', 'luo', 'luy', 'lv',
        'mas', 'mfe', 'mg', 'mgh', 'mk', 'ml', 'mn', 'mr', 'ms', 'ms-BN', 'mt',
        'mua', 'my', 'mzn', 'naq', 'nb', 'nds', 'ne', 'nl', 'nl-BE', 'nn',
        'nyn', 'om', 'or', 'pa', 'pa-Arab', 'pl', 'prg', 'pt', 'pt-AO',
        'pt-CH', 'pt-CV', 'pt-GQ', 'pt-GW', 'pt-LU', 'pt-MO', 'pt-MZ', 'pt-PT',
        'pt-ST', 'pt-TL', 'qu', 'qu-BO', 'rm', 'rn', 'ro', 'rof', 'ru', 'rw',
        'sd', 'se', 'seh', 'ses', 'sg', 'si', 'sk', 'sl', 'smn', 'so', 'sq',
        'sr', 'sv', 'sw', 'sw-CD', 'ta', 'ta-MY', 'ta-SG', 'te', 'tg', 'th',
        'ti', 'tk', 'to', 'tr', 'tt', 'twq', 'tzm', 'ug', 'uk', 'ur', 'ur-IN',
        'uz', 'uz-Arab', 'vi', 'vo', 'wae', 'wo', 'yav', 'yi', 'yo', 'zh', 'zu',
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
            $this->numberFormats[$locale] = $this->createNumberFormatFromDefinition($definition, $locale);
        }

        return $this->numberFormats[$locale];
    }

    /**
     * Creates a number format object from the provided definition.
     *
     * @param array  $definition The number format definition.
     * @param string $locale     The locale of the number format definition.
     *
     * @return NumberFormat
     */
    protected function createNumberFormatFromDefinition(array $definition, $locale)
    {
        if (!isset($definition['decimal_separator'])) {
            $definition['decimal_separator'] = '.';
        }
        if (!isset($definition['grouping_separator'])) {
            $definition['grouping_separator'] = ',';
        }
        if (!isset($definition['plus_sign'])) {
            $definition['plus_sign'] = '+';
        }
        if (!isset($definition['minus_sign'])) {
            $definition['minus_sign'] = '-';
        }
        if (!isset($definition['percent_sign'])) {
            $definition['percent_sign'] = '%';
        }

        $numberFormat = new NumberFormat();
        $setValues = \Closure::bind(function ($definition, $locale) {
            $this->locale = $locale;
            $this->numberingSystem = $definition['numbering_system'];
            $this->decimalSeparator = $definition['decimal_separator'];
            $this->groupingSeparator = $definition['grouping_separator'];
            $this->plusSign = $definition['plus_sign'];
            $this->minusSign = $definition['minus_sign'];
            $this->percentSign = $definition['percent_sign'];
            $this->decimalPattern = $definition['decimal_pattern'];
            $this->percentPattern = $definition['percent_pattern'];
            $this->currencyPattern = $definition['currency_pattern'];
            $this->accountingCurrencyPattern = $definition['accounting_currency_pattern'];
        }, $numberFormat, '\CommerceGuys\Intl\NumberFormat\NumberFormat');
        $setValues($definition, $locale);

        return $numberFormat;
    }
}
