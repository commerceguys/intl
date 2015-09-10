<?php

namespace CommerceGuys\Intl\Country;

class Country implements CountryEntityInterface
{
    /**
     * The two-letter country code.
     *
     * @var string
     */
    protected $countryCode;

    /**
     * The country name.
     *
     * @var string
     */
    protected $name;

    /**
     * The three-letter country code.
     *
     * @var string
     */
    protected $threeLetterCode;

    /**
     * The numeric country code.
     *
     * @var string
     */
    protected $numericCode;

    /**
     * The country currency code.
     *
     * @var string
     */
    protected $currencyCode;

    /**
     * The country locale (i.e. "en_US").
     *
     * The country name is locale specific.
     *
     * @var string
     */
    protected $locale;

    /**
     * Returns the string representation of the Country.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getCountryCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getThreeLetterCode()
    {
        return $this->threeLetterCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setThreeLetterCode($threeLetterCode)
    {
        $this->threeLetterCode = $threeLetterCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNumericCode()
    {
        return $this->numericCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setNumericCode($numericCode)
    {
        $this->numericCode = $numericCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
