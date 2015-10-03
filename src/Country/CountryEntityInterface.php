<?php

namespace CommerceGuys\Intl\Country;

interface CountryEntityInterface extends CountryInterface
{
    /**
     * Sets the two-letter country code.
     *
     * @param string $countryCode The two-letter country code.
     *
     * @return self
     */
    public function setCountryCode($countryCode);

    /**
     * Sets the country name.
     *
     * @param string $name The country name.
     *
     * @return self
     */
    public function setName($name);

    /**
     * Sets the three-letter country code.
     *
     * @param string $threeLetterCode The three-letter country code.
     *
     * @return self
     */
    public function setThreeLetterCode($threeLetterCode);

    /**
     * Sets the numeric country code.
     *
     * @param string $numericCode The numeric country code.
     *
     * @return self
     */
    public function setNumericCode($numericCode);

    /**
     * Sets the country currency code.
     *
     * @param string $currencyCode The currency code.
     *
     * @return self
     */
    public function setCurrencyCode($currencyCode);
}
