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
     * Sets the two-letter country code.
     *
     * @param string $countryCode The two-letter country code.
     */
    public function setCountryCode($countryCode);

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
     * Sets the country name.
     *
     * @param string $name The country name.
     */
    public function setName($name);

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
     * Sets the three-letter country code.
     *
     * @param string $threeLetterCode The three-letter country code.
     */
    public function setThreeLetterCode($threeLetterCode);

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
     * Sets the numeric country code.
     *
     * @param string $numericCode The numeric country code.
     */
    public function setNumericCode($numericCode);

    /**
     * Gets the country telephone code.
     *
     * Also known as the calling code.
     *
     * Note that not every country has a telephone code.
     * Right now Tristan da Cunha (TI) is the only such example.
     *
     * @return string|null
     */
    public function getTelephoneCode();

    /**
     * Sets the country telephone code.
     *
     * @param string $telephoneCode The telephone code.
     */
    public function setTelephoneCode($telephoneCode);
}
