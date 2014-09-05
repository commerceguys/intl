<?php

namespace CommerceGuys\Intl\Country;

/**
 * Country manager interface.
 */
interface CountryManagerInterface
{
    /**
     * Returns a country instance matching the provided country code.
     *
     * @param string $countryCode The country code.
     * @param string $locale The locale (i.e. fr_FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return \CommerceGuys\Intl\Country\CountryInterface
     */
    public function get($countryCode, $locale = 'en', $fallbackLocale = null);

    /**
     * Returns all available country instances.
     *
     * @param string $locale The locale (i.e. fr_FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return array An array of countries implementing the CountryInterface,
     *               keyed by country code.
     */
    public function getAll($locale = 'en', $fallbackLocale = null);
}
