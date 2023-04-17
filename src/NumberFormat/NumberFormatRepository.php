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
    protected string $fallbackLocale;

    /**
     * Creates a NumberFormatRepository instance.
     *
     * @param string $fallbackLocale The fallback locale. Defaults to 'en'.
     */
    public function __construct(string $fallbackLocale = 'en')
    {
        $this->fallbackLocale = $fallbackLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $locale): NumberFormat
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
    protected function processDefinition(string $locale, array $definition)
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
    protected function getDefinitions(): array
    {
        return [
            'ab' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'af' => [
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'an' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ann' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'apc' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ar' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-AE' => [
                'currency_pattern' => '‏#,##0.00 ¤;‏-#,##0.00 ¤',
                'accounting_currency_pattern' => '؜#,##0.00¤;(؜#,##0.00¤)',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ar-DZ' => [
                'currency_pattern' => '‏#,##0.00 ¤;‏-#,##0.00 ¤',
                'accounting_currency_pattern' => '؜#,##0.00¤;(؜#,##0.00¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ar-EH' => [
                'currency_pattern' => '‏#,##0.00 ¤;‏-#,##0.00 ¤',
                'accounting_currency_pattern' => '؜#,##0.00¤;(؜#,##0.00¤)',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ar-LY' => [
                'currency_pattern' => '‏#,##0.00 ¤;‏-#,##0.00 ¤',
                'accounting_currency_pattern' => '؜#,##0.00¤;(؜#,##0.00¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ar-MA' => [
                'currency_pattern' => '‏#,##0.00 ¤;‏-#,##0.00 ¤',
                'accounting_currency_pattern' => '؜#,##0.00¤;(؜#,##0.00¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ar-TN' => [
                'currency_pattern' => '‏#,##0.00 ¤;‏-#,##0.00 ¤',
                'accounting_currency_pattern' => '؜#,##0.00¤;(؜#,##0.00¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'arn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'as' => [
                'numbering_system' => 'beng',
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤ #,##,##0.00',
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
            'az-Arab' => [
                'numbering_system' => 'arabext',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+‎',
                'minus_sign' => '‎-‎',
                'percent_sign' => '٪',
            ],
            'az-Cyrl' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ba' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'bal' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'bal-Latn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'be' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'bg' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'bgc' => [
                'numbering_system' => 'deva',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'bgn' => [
                'numbering_system' => 'arabext',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+‎',
                'minus_sign' => '‎-‎',
                'percent_sign' => '٪',
            ],
            'bho' => [
                'numbering_system' => 'deva',
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'blt' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'bn' => [
                'numbering_system' => 'beng',
                'decimal_pattern' => '#,##,##0.###',
                'currency_pattern' => '#,##,##0.00¤',
                'accounting_currency_pattern' => '#,##,##0.00¤;(#,##,##0.00¤)',
            ],
            'bn-IN' => [
                'numbering_system' => 'beng',
                'decimal_pattern' => '#,##,##0.###',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00;(¤#,##,##0.00)',
            ],
            'bo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'brx' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤ #,##,##0.00',
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
            'bss' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'byn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ca' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'cch' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ce' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
            ],
            'cho' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'cic' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'co' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'cs' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'cv' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
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
                'grouping_currency_separator' => '.',
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
            'doi' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'dz' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0 %',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
            ],
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
            'en-Dsrt' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
                'currency_pattern' => '¤#,##,##0.00',
            ],
            'en-MV' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'es-MX' => [
                'accounting_currency_pattern' => '¤#,##0.00',
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
            'fi' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'fil' => [],
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
                'decimal_currency_separator' => '.',
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
            'frr' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ga' => [],
            'gaa' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'gd' => [],
            'gl' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'gn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'he' => [
                'currency_pattern' => '‏#,##0.00 ‏¤;‏-#,##0.00 ‏¤',
                'accounting_currency_pattern' => '‏#,##0.00 ‏¤;‏-#,##0.00 ‏¤',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
            ],
            'hi' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
            ],
            'hi-Latn' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
            ],
            'hnj' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'hr' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
                'minus_sign' => '−',
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
            'io' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
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
            'jbo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ka' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'kaj' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'kcg' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ken' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'ko' => [],
            'kok' => [
                'currency_pattern' => '¤ #,##0.00',
            ],
            'kpe' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ks' => [
                'numbering_system' => 'arabext',
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+‎',
                'minus_sign' => '‎-‎',
                'percent_sign' => '٪',
            ],
            'ks-Deva' => [
                'accounting_currency_pattern' => '¤#,##0.00',
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
            'lij' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'lo' => [
                'currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'lt' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'lv' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'mai' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mdf' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mg' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'mk' => [
                'percent_pattern' => '#,##0 %',
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
            'mn-Mong-MN' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
            ],
            'mni' => [
                'numbering_system' => 'beng',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mni-Mtei' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'moh' => [
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
            'ms-ID' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'mt' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'mus' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'my' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'myv' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ne' => [
                'numbering_system' => 'deva',
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤ #,##,##0.00',
                'accounting_currency_pattern' => '¤ #,##,##0.00',
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
            'no' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤ #,##0.00;¤ -#,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;(¤ #,##0.00)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'minus_sign' => '−',
            ],
            'nqo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'grouping_separator' => '،',
            ],
            'nv' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ny' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'osa' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'pa' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤#,##,##0.00',
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
            'pap' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'pis' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'pl' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'ps' => [
                'numbering_system' => 'arabext',
                'currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '‎+‎',
                'minus_sign' => '‎-‎',
                'percent_sign' => '٪',
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
            'quc' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'raj' => [
                'numbering_system' => 'deva',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'rhg' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'rif' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'sat' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'sat-Deva' => [
                'numbering_system' => 'deva',
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'scn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'sd-Deva' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'sdh' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'shn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'sma' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'smj' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'sms' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'ss' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'ssy' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'st' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
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
            'syr' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'szl' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ta' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤#,##,##0.00',
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
            ],
            'tg' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'th' => [],
            'tig' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'tok' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'tpi' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
            ],
            'tr' => [
                'percent_pattern' => '%#,##0',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'trv' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'trw' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'uk' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'ur' => [
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
            'vec' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'vi' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'wa' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'wbp' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'yue' => [],
            'yue-Hans' => [],
            'zh' => [],
            'zh-Hant' => [],
        ];
    }
}
