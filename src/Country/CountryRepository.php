<?php

namespace CommerceGuys\Intl\Country;

use CommerceGuys\Intl\LocaleResolverTrait;
use CommerceGuys\Intl\Exception\UnknownCountryException;

/**
 * Manages countries based on JSON definitions.
 */
class CountryRepository implements CountryRepositoryInterface
{
    use LocaleResolverTrait;

    /**
     * Base country definitions.
     *
     * Contains data common to all locales, such as the country numeric,
     * three-letter, telephone codes.
     *
     * @var array
     */
    protected $baseDefinitions = array();

    /**
     * Per-locale country definitions.
     *
     * @var array
     */
    protected $definitions = array();

    /**
     * Creates a CountryRepository instance.
     *
     * @param string $definitionPath The path to the country definitions.
     *                               Defaults to 'resources/country'.
     */
    public function __construct($definitionPath = null)
    {
        $this->definitionPath = $definitionPath ? $definitionPath : __DIR__ . '/../../resources/country/';
    }

    /**
     * {@inheritdoc}
     */
    public function get($countryCode, $locale = null, $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        if (!isset($definitions[$countryCode])) {
            throw new UnknownCountryException($countryCode);
        }

        return $this->createCountryFromDefinition($definitions[$countryCode], $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll($locale = null, $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
        $definitions = $this->loadDefinitions($locale);
        $countries = array();
        foreach ($definitions as $countryCode => $definition) {
            $countries[$countryCode] = $this->createCountryFromDefinition($definition, $locale);
        }

        return $countries;
    }

    /**
     * Loads the country definitions for the provided locale.
     *
     * @param string $locale The desired locale.
     *
     * @return array
     */
    protected function loadDefinitions($locale)
    {
        if (!isset($this->definitions[$locale])) {
            $filename = $this->definitionPath . $locale . '.json';
            $this->definitions[$locale] = json_decode(file_get_contents($filename), true);

            // Make sure the base definitions have been loaded.
            if (empty($this->baseDefinitions)) {
                $this->baseDefinitions = json_decode(file_get_contents($this->definitionPath . 'base.json'), true);
            }
            // Merge-in base definitions.
            foreach ($this->definitions[$locale] as $countryCode => $definition) {
                $this->definitions[$locale][$countryCode] += $this->baseDefinitions[$countryCode];
            }
        }

        return $this->definitions[$locale];
    }

    /**
     * Creates a country object from the provided definition.
     *
     * @param array  $definition The country definition.
     * @param string $locale     The locale of the country definition.
     *
     * @return Country
     */
    protected function createCountryFromDefinition(array $definition, $locale)
    {
        $country = new Country();
        $country->setCountryCode($definition['code']);
        $country->setName($definition['name']);
        $country->setLocale($locale);
        if (isset($definition['three_letter_code'])) {
            $country->setThreeLetterCode($definition['three_letter_code']);
        }
        if (isset($definition['numeric_code'])) {
            $country->setNumericCode($definition['numeric_code']);
        }
        if (isset($definition['telephone_code'])) {
            $country->setTelephoneCode($definition['telephone_code']);
        }

        return $country;
    }
}
