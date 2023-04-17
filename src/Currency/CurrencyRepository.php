<?php

namespace CommerceGuys\Intl\Currency;

use CommerceGuys\Intl\Locale;
use CommerceGuys\Intl\Exception\UnknownCurrencyException;

/**
 * Manages currencies based on JSON definitions.
 */
class CurrencyRepository implements CurrencyRepositoryInterface
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
     * Per-locale currency definitions.
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
        'af', 'am', 'ar', 'as', 'az', 'be', 'bg', 'bn', 'bn-IN', 'bs', 'ca',
        'cs', 'cy', 'da', 'de', 'de-CH', 'el', 'el-polyton', 'en', 'en-001',
        'en-AU', 'en-CA', 'en-GG', 'en-IM', 'en-JE', 'es', 'es-419', 'es-CL',
        'es-GT', 'es-MX', 'es-US', 'es-VE', 'et', 'eu', 'fa', 'fa-AF', 'fi',
        'fil', 'fr', 'fr-CA', 'ga', 'gd', 'gl', 'gu', 'he', 'hi', 'hi-Latn',
        'hr', 'hu', 'hy', 'id', 'ig', 'is', 'it', 'ja', 'ka', 'kk', 'km', 'ko',
        'kok', 'ky', 'lo', 'lt', 'lv', 'mk', 'mn', 'mr', 'ms', 'my', 'ne', 'nl',
        'nn', 'no', 'or', 'pa', 'pcm', 'pl', 'ps', 'pt', 'pt-PT', 'ro', 'ru',
        'si', 'sk', 'sl', 'so', 'sq', 'sr', 'sr-Cyrl-BA', 'sr-Latn',
        'sr-Latn-BA', 'sv', 'sw', 'sw-CD', 'sw-KE', 'ta', 'te', 'th', 'tk',
        'tr', 'uk', 'ur', 'ur-IN', 'uz', 'vi', 'yue', 'yue-Hans', 'zh',
        'zh-Hans-HK', 'zh-Hant', 'zh-Hant-HK', 'zu'
    ];

    /**
     * Creates a CurrencyRepository instance.
     *
     * @param string $defaultLocale  The default locale. Defaults to 'en'.
     * @param string $fallbackLocale The fallback locale. Defaults to 'en'.
     * @param string|null $definitionPath The path to the currency definitions.
     *                               Defaults to 'resources/currency'.
     */
    public function __construct(string $defaultLocale = 'en', string $fallbackLocale = 'en', string $definitionPath = null)
    {
        $this->defaultLocale = $defaultLocale;
        $this->fallbackLocale = $fallbackLocale;
        $this->definitionPath = $definitionPath ?: __DIR__ . '/../../resources/currency/';
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $currencyCode, string $locale = null): Currency
    {
        $currencyCode = strtoupper($currencyCode);
        $baseDefinitions = $this->getBaseDefinitions();
        if (!isset($baseDefinitions[$currencyCode])) {
            throw new UnknownCurrencyException($currencyCode);
        }
        $locale = $locale ?: $this->defaultLocale;
        $locale = Locale::resolve($this->availableLocales, $locale, $this->fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $currency = new Currency([
            'currency_code' => $currencyCode,
            'numeric_code' => $baseDefinitions[$currencyCode][0],
            'fraction_digits' => $baseDefinitions[$currencyCode][1],
            'locale' => $locale,
        ] + $definitions[$currencyCode]);

        return $currency;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(string $locale = null): array
    {
        $locale = $locale ?: $this->defaultLocale;
        $locale = Locale::resolve($this->availableLocales, $locale, $this->fallbackLocale);
        $baseDefinitions = $this->getBaseDefinitions();
        $definitions = $this->loadDefinitions($locale);
        $currencies = [];
        foreach ($definitions as $currencyCode => $definition) {
            $currencies[$currencyCode] = new Currency([
                'currency_code' => $currencyCode,
                'numeric_code' => $baseDefinitions[$currencyCode][0],
                'fraction_digits' => $baseDefinitions[$currencyCode][1],
                'locale' => $locale,
            ] + $definition);
        }

        return $currencies;
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
        foreach ($definitions as $currencyCode => $definition) {
            $list[$currencyCode] = $definition['name'];
        }

        return $list;
    }

    /**
     * Loads the currency definitions for the provided locale.
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

    /**
     * Gets the base currency definitions.
     *
     * Contains data common to all locales: numeric code, fraction digits.
     *
     * @return array
     *   An array of definitions, keyed by currency code.
     *   Each definition is a numerically indexed array containing:
     *   - The numeric code.
     *   - The fraction digits.
     */
    protected function getBaseDefinitions(): array
    {
        return [
            'AED' => ['784', 2],
            'AFN' => ['971', 0],
            'ALL' => ['008', 0],
            'AMD' => ['051', 2],
            'ANG' => ['532', 2],
            'AOA' => ['973', 2],
            'ARS' => ['032', 2],
            'AUD' => ['036', 2],
            'AWG' => ['533', 2],
            'AZN' => ['944', 2],
            'BAM' => ['977', 2],
            'BBD' => ['052', 2],
            'BDT' => ['050', 2],
            'BGN' => ['975', 2],
            'BHD' => ['048', 3],
            'BIF' => ['108', 0],
            'BMD' => ['060', 2],
            'BND' => ['096', 2],
            'BOB' => ['068', 2],
            'BRL' => ['986', 2],
            'BSD' => ['044', 2],
            'BTN' => ['064', 2],
            'BWP' => ['072', 2],
            'BYN' => ['933', 2],
            'BZD' => ['084', 2],
            'CAD' => ['124', 2],
            'CDF' => ['976', 2],
            'CHF' => ['756', 2],
            'CLP' => ['152', 0],
            'CNY' => ['156', 2],
            'COP' => ['170', 2],
            'CRC' => ['188', 2],
            'CUC' => ['931', 2],
            'CUP' => ['192', 2],
            'CVE' => ['132', 2],
            'CZK' => ['203', 2],
            'DJF' => ['262', 0],
            'DKK' => ['208', 2],
            'DOP' => ['214', 2],
            'DZD' => ['012', 2],
            'EGP' => ['818', 2],
            'ERN' => ['232', 2],
            'ETB' => ['230', 2],
            'EUR' => ['978', 2],
            'FJD' => ['242', 2],
            'FKP' => ['238', 2],
            'GBP' => ['826', 2],
            'GEL' => ['981', 2],
            'GHS' => ['936', 2],
            'GIP' => ['292', 2],
            'GMD' => ['270', 2],
            'GNF' => ['324', 0],
            'GTQ' => ['320', 2],
            'GYD' => ['328', 2],
            'HKD' => ['344', 2],
            'HNL' => ['340', 2],
            'HTG' => ['332', 2],
            'HUF' => ['348', 2],
            'IDR' => ['360', 2],
            'ILS' => ['376', 2],
            'INR' => ['356', 2],
            'IQD' => ['368', 0],
            'IRR' => ['364', 0],
            'ISK' => ['352', 0],
            'JMD' => ['388', 2],
            'JOD' => ['400', 3],
            'JPY' => ['392', 0],
            'KES' => ['404', 2],
            'KGS' => ['417', 2],
            'KHR' => ['116', 2],
            'KMF' => ['174', 0],
            'KPW' => ['408', 0],
            'KRW' => ['410', 0],
            'KWD' => ['414', 3],
            'KYD' => ['136', 2],
            'KZT' => ['398', 2],
            'LAK' => ['418', 0],
            'LBP' => ['422', 0],
            'LKR' => ['144', 2],
            'LRD' => ['430', 2],
            'LSL' => ['426', 2],
            'LYD' => ['434', 3],
            'MAD' => ['504', 2],
            'MDL' => ['498', 2],
            'MGA' => ['969', 0],
            'MKD' => ['807', 2],
            'MMK' => ['104', 0],
            'MNT' => ['496', 2],
            'MOP' => ['446', 2],
            'MRU' => ['929', 2],
            'MUR' => ['480', 2],
            'MVR' => ['462', 2],
            'MWK' => ['454', 2],
            'MXN' => ['484', 2],
            'MYR' => ['458', 2],
            'MZN' => ['943', 2],
            'NAD' => ['516', 2],
            'NGN' => ['566', 2],
            'NIO' => ['558', 2],
            'NOK' => ['578', 2],
            'NPR' => ['524', 2],
            'NZD' => ['554', 2],
            'OMR' => ['512', 3],
            'PAB' => ['590', 2],
            'PEN' => ['604', 2],
            'PGK' => ['598', 2],
            'PHP' => ['608', 2],
            'PKR' => ['586', 2],
            'PLN' => ['985', 2],
            'PYG' => ['600', 0],
            'QAR' => ['634', 2],
            'RON' => ['946', 2],
            'RSD' => ['941', 0],
            'RUB' => ['643', 2],
            'RWF' => ['646', 0],
            'SAR' => ['682', 2],
            'SBD' => ['090', 2],
            'SCR' => ['690', 2],
            'SDG' => ['938', 2],
            'SEK' => ['752', 2],
            'SGD' => ['702', 2],
            'SHP' => ['654', 2],
            'SLE' => ['925', 2],
            'SLL' => ['694', 0],
            'SOS' => ['706', 0],
            'SRD' => ['968', 2],
            'SSP' => ['728', 2],
            'STN' => ['930', 2],
            'SVC' => ['222', 2],
            'SYP' => ['760', 0],
            'SZL' => ['748', 2],
            'THB' => ['764', 2],
            'TJS' => ['972', 2],
            'TMT' => ['934', 2],
            'TND' => ['788', 3],
            'TOP' => ['776', 2],
            'TRY' => ['949', 2],
            'TTD' => ['780', 2],
            'TWD' => ['901', 2],
            'TZS' => ['834', 2],
            'UAH' => ['980', 2],
            'UGX' => ['800', 0],
            'USD' => ['840', 2],
            'UYU' => ['858', 2],
            'UYW' => ['927', 4],
            'UZS' => ['860', 2],
            'VED' => ['926', 2],
            'VES' => ['928', 2],
            'VND' => ['704', 0],
            'VUV' => ['548', 0],
            'WST' => ['882', 2],
            'XAF' => ['950', 0],
            'XCD' => ['951', 2],
            'XOF' => ['952', 0],
            'XPF' => ['953', 0],
            'YER' => ['886', 0],
            'ZAR' => ['710', 2],
            'ZMW' => ['967', 2],
            'ZWL' => ['932', 2],
        ];
    }
}
