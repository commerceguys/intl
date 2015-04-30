<?php

namespace CommerceGuys\Intl\NumberFormat;

use CommerceGuys\Intl\LocaleResolverTrait;

/**
 * Repository for number formats based on JSON definitions.
 */
class NumberFormatRepository implements NumberFormatRepositoryInterface
{
    use LocaleResolverTrait;

    /**
     * Number format definitions.
     *
     * @var array
     */
    protected $definitions = [];

    /**
     * Creates a NumberFormatRepository instance.
     *
     * @param string $definitionPath The path to the number format definitions.
     *                               Defaults to 'resources/number_format'.
     */
    public function __construct($definitionPath = null)
    {
        $this->definitionPath = $definitionPath ? $definitionPath : __DIR__ . '/../../resources/number_format/';
    }

    /**
     * {@inheritdoc}
     */
    public function get($locale, $fallbackLocale = null)
    {
        $locale = $this->resolveLocale($locale, $fallbackLocale);
        if (!isset($this->definitions[$locale])) {
            $filename = $this->definitionPath . $locale . '.json';
            $this->definitions[$locale] = json_decode(file_get_contents($filename), true);
        }

        return $this->createNumberFormatFromDefinition($this->definitions[$locale], $locale);
    }

    /**
     * Creates a number format object from the provided definition.
     *
     * @param array  $definition The number format definition.
     * @param string $locale     The locale of the number format definition.
     *
     * @return NumberFormat
     */
    protected function createNumberFormatFromDefinition(array $definition, $locale)
    {
        if (!isset($definition['decimal_separator'])) {
            $definition['decimal_separator'] = '.';
        }
        if (!isset($definition['grouping_separator'])) {
            $definition['grouping_separator'] = ',';
        }
        if (!isset($definition['plus_sign'])) {
            $definition['plus_sign'] = '+';
        }
        if (!isset($definition['minus_sign'])) {
            $definition['minus_sign'] = '-';
        }
        if (!isset($definition['percent_sign'])) {
            $definition['percent_sign'] = '%';
        }

        $numberFormat = new NumberFormat();
        $setValues = \Closure::bind(function ($definition, $locale) {
            $this->locale = $locale;
            $this->numberingSystem = $definition['numbering_system'];
            $this->decimalSeparator = $definition['decimal_separator'];
            $this->groupingSeparator = $definition['grouping_separator'];
            $this->plusSign = $definition['plus_sign'];
            $this->minusSign = $definition['minus_sign'];
            $this->percentSign = $definition['percent_sign'];
            $this->decimalPattern = $definition['decimal_pattern'];
            $this->percentPattern = $definition['percent_pattern'];
            $this->currencyPattern = $definition['currency_pattern'];
            $this->accountingCurrencyPattern = $definition['accounting_currency_pattern'];
        }, $numberFormat, '\CommerceGuys\Intl\NumberFormat\NumberFormat');
        $setValues($definition, $locale);

        return $numberFormat;
    }
}
