<?php

namespace CommerceGuys\Intl\Currency;

use CommerceGuys\Intl\LocaleResolverTrait;
use CommerceGuys\Intl\Exception\UnknownCurrencyException;

/**
 * Manages currencies based on JSON definitions.
 */
class CurrencyRepository implements CurrencyRepositoryInterface
{
    use LocaleResolverTrait;

    /**
     * Base currency definitions.
     *
     * Contains data common to all locales, such as the currency numeric
     * code, number of fraction digits.
     *
     * @var array
     */
    protected $baseDefinitions = [];

    /**
     * Per-locale currency definitions.
     *
     * @var array
     */
    protected $definitions = [];

    /**
     * Creates a CurrencyRepository instance.
     *
     * @param string $definitionPath The path to the currency definitions.
     *                               Defaults to 'resources/currency'.
     */
    public function __construct($definitionPath = null)
    {
        $this->definitionPath = $definitionPath ? $definitionPath : __DIR__ . '/../../resources/currency/';
    }

    /**
     * {@inheritdoc}
     */
    public function get($currencyCode, $locale = null, $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        if (!isset($definitions[$currencyCode])) {
            throw new UnknownCurrencyException($currencyCode);
        }

        return $this->createCurrencyFromDefinition($currencyCode, $definitions[$currencyCode], $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll($locale = null, $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $currencies = [];
        foreach ($definitions as $currencyCode => $definition) {
            $currencies[$currencyCode] = $this->createCurrencyFromDefinition($currencyCode, $definition, $locale);
        }

        return $currencies;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($locale = null, $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
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
            foreach ($this->definitions[$locale] as $currencyCode => $definition) {
                $this->definitions[$locale][$currencyCode] += $this->baseDefinitions[$currencyCode];
            }
        }

        return $this->definitions[$locale];
    }

    /**
     * Creates a currency object from the provided definition.
     *
     * @param string $currencyCode The currency code.
     * @param array  $definition   The currency definition.
     * @param string $locale       The locale of the currency definition.
     *
     * @return Currency
     */
    protected function createCurrencyFromDefinition($currencyCode, array $definition, $locale)
    {
        if (!isset($definition['symbol'])) {
            $definition['symbol'] = $currencyCode;
        }
        if (!isset($definition['fraction_digits'])) {
            $definition['fraction_digits'] = 2;
        }

        $currency = new Currency();
        $setValues = \Closure::bind(function ($currencyCode, $definition, $locale) {
            $this->currencyCode = $currencyCode;
            $this->name = $definition['name'];
            $this->numericCode = $definition['numeric_code'];
            $this->symbol = $definition['symbol'];
            $this->fractionDigits = $definition['fraction_digits'];
            $this->locale = $locale;
        }, $currency, '\CommerceGuys\Intl\Currency\Currency');
        $setValues($currencyCode, $definition, $locale);

        return $currency;
    }
}
