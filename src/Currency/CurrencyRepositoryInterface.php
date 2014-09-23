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
     * @param string $currencyCode The currency code.
     * @param string $locale The locale (i.e. fr_FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return \CommerceGuys\Intl\Currency\CurrencyInterface
     */
    public function get($currencyCode, $locale = 'en', $fallbackLocale = null);

    /**
     * Returns all available currency instances.
     *
     * @param string $locale The locale (i.e. fr_FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return array An array of currencies implementing the CurrencyInterface,
     *               keyed by currency code.
     */
    public function getAll($locale = 'en', $fallbackLocale = null);
}
