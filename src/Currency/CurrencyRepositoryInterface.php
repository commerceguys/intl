<?php

namespace CommerceGuys\Intl\Currency;

/**
 * Currency repository interface.
 */
interface CurrencyRepositoryInterface
{
    /**
     * Gets a currency matching the provided currency code.
     *
     * @param string $currencyCode   The currency code.
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return Currency
     */
    public function get($currencyCode, $locale = null, $fallbackLocale = null);

    /**
     * Gets all currencies.
     *
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return Currency[] An array of currencies, keyed by currency code.
     */
    public function getAll($locale = null, $fallbackLocale = null);

    /**
     * Gets a list of currencies.
     *
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return string[] An array of currency names, keyed by currency code.
     */
    public function getList($locale = null, $fallbackLocale = null);
}
