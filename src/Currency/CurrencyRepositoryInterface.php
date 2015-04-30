<?php

namespace CommerceGuys\Intl\Currency;

/**
 * Currency repository interface.
 */
interface CurrencyRepositoryInterface
{
    /**
     * Returns a currency instance matching the provided currency code.
     *
     * @param string $currencyCode   The currency code.
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return CurrencyInterface
     */
    public function get($currencyCode, $locale = null, $fallbackLocale = null);

    /**
     * Returns all currency instances.
     *
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return array An array of currencies implementing the CurrencyInterface,
     *               keyed by currency code.
     */
    public function getAll($locale = null, $fallbackLocale = null);

    /**
     * Returns a list of currencies.
     *
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return array An array of currency names, keyed by currency code.
     */
    public function getList($locale = null, $fallbackLocale = null);
}
