<?php

namespace CommerceGuys\Intl\NumberFormat;

use CommerceGuys\Intl\Locale;

/**
 * Provides number formats.
 */
class NumberFormatRepository implements NumberFormatRepositoryInterface
{
    /**
     * The fallback locale.
     *
     * @var string
     */
    protected $fallbackLocale;

    /**
     * Creates a NumberFormatRepository instance.
     *
     * @param string $fallbackLocale The fallback locale. Defaults to 'en'.
     */
    public function __construct($fallbackLocale = 'en')
    {
        $this->fallbackLocale = $fallbackLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function get($locale)
    {
        $definitions = $this->getDefinitions();
        $availableLocales = array_keys($definitions);
        $locale = Locale::resolve($availableLocales, $locale, $this->fallbackLocale);
        $definition = $this->processDefinition($locale, $definitions[$locale]);

        return new NumberFormat($definition);
    }

    /**
     * Processes the number format definition for the provided locale.
     *
     * @param string $locale    The locale.
     * @param array $definition The definition
     *
     * @return array The processed definition.
     */
    protected function processDefinition($locale, array $definition)
    {
        $definition['locale'] = $locale;
        // The generation script strips all keys that have the same values
        // as the ones in 'en'.
        if ($definition['locale'] != 'en') {
            $definitions = $this->getDefinitions();
            $definition += $definitions['en'];
        }

        return $definition;
    }

    /**
     * Gets the number format definitions.
     *
     * @return array
     *   The number format definitions, keyed by locale.
     */
    protected function getDefinitions()
    {
        return [
            'af' => [
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'agq' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'ak' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'am' => [],
            'ar' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-DZ' => [
                'currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ar-EH' => [
                'currency_pattern' => '¤ #,##0.00',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ar-LY' => [
                'currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ar-MA' => [
                'currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ar-TN' => [
                'currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ast' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'az' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'az-Cyrl' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'bas' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'be' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'bez' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
            ],
            'bg' => [
                'currency_pattern' => '#0.00 ¤',
                'accounting_currency_pattern' => '#0.00 ¤;(#0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'bm' => [],
            'bn' => [
                'numbering_system' => 'beng',
                'decimal_pattern' => '#,##,##0.###',
                'currency_pattern' => '#,##,##0.00¤',
                'accounting_currency_pattern' => '#,##,##0.00¤;(#,##,##0.00¤)',
            ],
            'bo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'br' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'brx' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤ #,##,##0.00',
                'accounting_currency_pattern' => '¤ #,##,##0.00',
            ],
            'bs' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'bs-Cyrl' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ca' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ca-ES-VALENCIA' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ce' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
            ],
            'ceb' => [
                'percent_pattern' => '#,#0%',
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'cgg' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'ckb' => [
                'numbering_system' => 'arab',
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‏+',
                'minus_sign' => '‏-',
                'percent_sign' => '٪',
            ],
            'cs' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'cu' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'cy' => [],
            'da' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'de' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'de-AT' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'de-CH' => [
                'currency_pattern' => '¤ #,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'grouping_separator' => '’',
            ],
            'de-LI' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'grouping_separator' => '’',
            ],
            'dje' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'grouping_separator' => ' ',
            ],
            'dsb' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'dyo' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'dz' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0 %',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
            ],
            'ee' => [],
            'el' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'en' => [
                'numbering_system' => 'latn',
                'decimal_pattern' => '#,##0.###',
                'percent_pattern' => '#,##0%',
                'currency_pattern' => '¤#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00;(¤#,##0.00)',
            ],
            'en-150' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
            ],
            'en-AT' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'en-BE' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'en-CH' => [
                'currency_pattern' => '¤ #,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;¤-#,##0.00',
                'grouping_separator' => '’',
            ],
            'en-DE' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'en-DK' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'en-FI' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'en-IN' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤ #,##,##0.00',
            ],
            'en-NL' => [
                'currency_pattern' => '¤ #,##0.00;¤ -#,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;(¤ #,##0.00)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'en-SE' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'en-SI' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'en-ZA' => [
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'eo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'es' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-419' => [
                'percent_pattern' => '#,##0 %',
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'es-AR' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;(¤ #,##0.00)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-BO' => [
                'percent_pattern' => '#,##0 %',
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-CL' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-CO' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-CR' => [
                'percent_pattern' => '#,##0 %',
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'es-DO' => [
                'percent_pattern' => '#,##0 %',
            ],
            'es-EC' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-GQ' => [
                'percent_pattern' => '#,##0 %',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-PE' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'es-PY' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00;¤ -#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-UY' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;(¤ #,##0.00)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-VE' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'et' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'eu' => [
                'percent_pattern' => '% #,##0',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'minus_sign' => '−',
            ],
            'fa' => [
                'numbering_system' => 'arabext',
                'currency_pattern' => '‎¤#,##0.00',
                'accounting_currency_pattern' => '‎¤ #,##0.00;‎(¤ #,##0.00)',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+',
                'minus_sign' => '‎−',
                'percent_sign' => '٪',
            ],
            'fa-AF' => [
                'numbering_system' => 'arabext',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;‎(¤ #,##0.00)',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+',
                'minus_sign' => '‎−',
                'percent_sign' => '٪',
            ],
            'ff' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'fi' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'fil' => [],
            'fo' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'minus_sign' => '−',
            ],
            'fr' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'fr-CA' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'fr-CH' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'fr-LU' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'fr-MA' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'fur' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'fy' => [
                'currency_pattern' => '¤ #,##0.00;¤ #,##0.00-',
                'accounting_currency_pattern' => '¤ #,##0.00;(¤ #,##0.00)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ga' => [],
            'gd' => [],
            'gl' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'gsw' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'grouping_separator' => '’',
                'minus_sign' => '−',
            ],
            'gu' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00;(¤#,##,##0.00)',
            ],
            'ha' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'haw' => [],
            'he' => [
                'currency_pattern' => '‏#,##0.00 ¤;‏-#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
            ],
            'hi' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
            ],
            'hr' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'hsb' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'hu' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'hy' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'id' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ig' => [],
            'is' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'it' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'it-CH' => [
                'currency_pattern' => '¤ #,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'grouping_separator' => '’',
            ],
            'ja' => [],
            'jv' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ka' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'kab' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'kea' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'khq' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'grouping_separator' => ' ',
            ],
            'kk' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'km' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤;(#,##0.00¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'kn' => [],
            'ko' => [],
            'kok' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤ #,##,##0.00',
                'accounting_currency_pattern' => '¤ #,##,##0.00',
            ],
            'ks' => [
                'numbering_system' => 'arabext',
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤ #,##,##0.00',
                'accounting_currency_pattern' => '¤ #,##,##0.00',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+‎',
                'minus_sign' => '‎-‎',
                'percent_sign' => '٪',
            ],
            'ksf' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'ksh' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'ku' => [
                'percent_pattern' => '%#,##0',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ky' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'lb' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'lg' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
            ],
            'lkt' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'lo' => [
                'currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'lrc' => [
                'numbering_system' => 'arabext',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+‎',
                'minus_sign' => '‎-‎',
                'percent_sign' => '٪',
            ],
            'lt' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'lu' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'luo' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
            ],
            'luy' => [
                'currency_pattern' => '¤#,##0.00;¤- #,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00;¤- #,##0.00',
            ],
            'lv' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'mas' => [],
            'mfe' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'grouping_separator' => ' ',
            ],
            'mg' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'mgh' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'mi' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mk' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ml' => [
                'decimal_pattern' => '#,##,##0.###',
            ],
            'mn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mr' => [
                'numbering_system' => 'deva',
                'decimal_pattern' => '#,##,##0.###',
            ],
            'ms' => [],
            'ms-BN' => [
                'currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'mt' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'mua' => [
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'my' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mzn' => [
                'numbering_system' => 'arabext',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+‎',
                'minus_sign' => '‎-‎',
                'percent_sign' => '٪',
            ],
            'naq' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'nb' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'nds' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ne' => [
                'numbering_system' => 'deva',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'nl' => [
                'currency_pattern' => '¤ #,##0.00;¤ -#,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;(¤ #,##0.00)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'nn' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'nyn' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'om' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'or' => [
                'decimal_pattern' => '#,##,##0.###',
            ],
            'pa' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤ #,##,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'pa-Arab' => [
                'numbering_system' => 'arabext',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+‎',
                'minus_sign' => '‎-‎',
                'percent_sign' => '٪',
            ],
            'pl' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'prg' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'pt' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'pt-PT' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'qu' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'qu-BO' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'rm' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'grouping_separator' => '’',
                'minus_sign' => '−',
            ],
            'rn' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ro' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'rof' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'ru' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'rw' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'sd' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'se' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'seh' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ses' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'grouping_separator' => ' ',
            ],
            'sg' => [
                'currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'si' => [],
            'sk' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'sl' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'minus_sign' => '−',
            ],
            'smn' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'so' => [],
            'sq' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'sr' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'sr-Latn' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'sv' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'sw' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'sw-CD' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ta' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤ #,##,##0.00',
            ],
            'ta-MY' => [
                'currency_pattern' => '¤ #,##0.00',
            ],
            'ta-SG' => [
                'currency_pattern' => '¤ #,##0.00',
            ],
            'te' => [
                'decimal_pattern' => '#,##,##0.###',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00;(¤#,##,##0.00)',
            ],
            'tg' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'th' => [],
            'ti' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'tk' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'to' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'tr' => [
                'percent_pattern' => '%#,##0',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'tt' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'twq' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'grouping_separator' => ' ',
            ],
            'tzm' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'ug' => [],
            'uk' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'ur' => [
                'currency_pattern' => '¤ #,##0.00',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
            ],
            'ur-IN' => [
                'numbering_system' => 'arabext',
                'currency_pattern' => '¤ #,##,##0.00',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+‎',
                'minus_sign' => '‎-‎',
            ],
            'uz' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'uz-Arab' => [
                'numbering_system' => 'arabext',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+‎',
                'minus_sign' => '‎-‎',
                'percent_sign' => '٪',
            ],
            'uz-Cyrl' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'vi' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'vo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'wae' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '’',
            ],
            'wo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'xh' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'grouping_separator' => ' ',
            ],
            'yav' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'yi' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'yo' => [],
            'yue' => [],
            'yue-Hans' => [],
            'zh' => [],
            'zh-Hant' => [],
            'zu' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
        ];
    }
}
