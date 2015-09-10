<?php

namespace CommerceGuys\Intl\Country;

interface CountryInterface
{
    /**
     * Gets the two-letter country code.
     *
     * @return string
     */
    public function getCountryCode();

    /**
     * Gets the country name.
     *
     * Note that certain locales have incomplete translations, in which
     * case the english version of the country name is used instead.
     *
     * @return string
     */
    public function getName();

    /**
     * Gets the three-letter country code.
     *
     * Note that not every country has a three-letter code.
     * CLDR lists "Canary Islands" (IC) and "Ceuta and Melilla" (EA)
     * as separate countries, even though they are formally a part of Spain
     * and have no three-letter or numeric ISO codes.
     *
     * @return string|null
     */
    public function getThreeLetterCode();

    /**
     * Gets the numeric country code.
     *
     * The numeric code has three digits, and the first one can be a zero,
     * hence the need to pass it around as a string.
     *
     * Note that not every country has a numeric code.
     * CLDR lists "Canary Islands" (IC) and "Ceuta and Melilla" (EA)
     * as separate countries, even though they are formally a part of Spain
     * and have no three-letter or numeric ISO codes.
     * "Ascension Island" (AE) also has no numeric code, even though it has a
     * three-letter code.
     *
     * @return string|null
     */
    public function getNumericCode();

    /**
     * Gets the country currency code.
     *
     * Represents the default currency used in the country, if known.
     *
     * @return string|null
     */
    public function getCurrencyCode();
}
