<?php

namespace CommerceGuys\Intl\Country;

/**
 * Country repository interface.
 */
interface CountryRepositoryInterface
{
    /**
     * Returns a country instance matching the provided country code.
     *
     * @param string $countryCode    The country code.
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return CountryInterface
     */
    public function get($countryCode, $locale = null, $fallbackLocale = null);

    /**
     * Returns all country instances.
     *
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return array An array of countries implementing the CountryInterface,
     *               keyed by country code.
     */
    public function getAll($locale = null, $fallbackLocale = null);

    /**
     * Returns a list of countries.
     *
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return array An array of country names, keyed by country code.
     */
    public function getList($locale = null, $fallbackLocale = null);
}
