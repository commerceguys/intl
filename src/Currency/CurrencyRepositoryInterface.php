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
     * @param string $currencyCode The currency code.
     * @param string $locale       The locale (i.e. fr-FR).
     *
     * @return Currency
     */
    public function get($currencyCode, $locale = null);

    /**
     * Gets all currencies.
     *
     * @param string $locale The locale (i.e. fr-FR).
     *
     * @return Currency[] An array of currencies, keyed by currency code.
     */
    public function getAll($locale = null);

    /**
     * Gets a list of currencies.
     *
     * @param string $locale The locale (i.e. fr-FR).
     *
     * @return string[] An array of currency names, keyed by currency code.
     */
    public function getList($locale = null);
}
