<?php

namespace CommerceGuys\Intl\Currency;

use CommerceGuys\Intl\Exception\UnknownCurrencyException;

/**
 * Currency repository interface.
 */
interface CurrencyRepositoryInterface
{
    /**
     * Gets a currency matching the provided currency code.
     *
     * @param string $currencyCode The currency code.
     * @param string|null $locale       The locale (i.e. fr-FR).
     *
     * @return Currency
     *
     * @throws UnknownCurrencyException
     */
    public function get(string $currencyCode, string $locale = null): Currency;

    /**
     * Gets all currencies.
     *
     * @param string|null $locale The locale (i.e. fr-FR).
     *
     * @return Currency[] An array of currencies, keyed by currency code.
     */
    public function getAll(string $locale = null): array;

    /**
     * Gets a list of currencies.
     *
     * @param string|null $locale The locale (i.e. fr-FR).
     *
     * @return string[] An array of currency names, keyed by currency code.
     */
    public function getList(string $locale = null): array;
}
