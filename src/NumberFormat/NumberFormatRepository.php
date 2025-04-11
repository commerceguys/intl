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
            'aa' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ab' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
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
            'ak' => [],
            'am' => [],
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
                'currency_pattern' => '‏#,##0.00 ¤;‏-#,##0.00 ¤',
                'accounting_currency_pattern' => '؜#,##0.00¤;(؜#,##0.00¤)',
                'plus_sign' => '‎+',
                'minus_sign' => '‎-',
                'percent_sign' => '‎%‎',
            ],
            'ar-BH' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-DJ' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
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
            'ar-EG' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-ER' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-IL' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-IQ' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-JO' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-KM' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-KW' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-LB' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
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
            'ar-MR' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-OM' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-PS' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-QA' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-SA' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-SD' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-SO' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-SS' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-SY' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
            ],
            'ar-TD' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
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
            'ar-YE' => [
                'numbering_system' => 'arab',
                'currency_pattern' => '‏#,##0.00 ¤',
                'accounting_currency_pattern' => '‏#,##0.00 ¤',
                'decimal_separator' => '٫',
                'grouping_separator' => '٬',
                'plus_sign' => '؜+',
                'minus_sign' => '؜-',
                'percent_sign' => '٪؜',
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
            'asa' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
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
            'bem' => [],
            'bew' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'bez' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
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
            'blo' => [
                'percent_pattern' => '% #,#0;% -#,#0',
                'currency_pattern' => '¤ #,##0.00;¤ -#,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;¤ -#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
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
            ],
            'bs' => [
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
            'ca' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'cad' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'cch' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ccp' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '#,##,##0.00¤',
                'accounting_currency_pattern' => '#,##,##0.00¤;(#,##,##0.00¤)',
            ],
            'ce' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
            ],
            'ceb' => [],
            'cgg' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'cho' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'chr' => [],
            'cic' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'co' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'cop' => [
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
            'csw' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'dav' => [],
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
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
                'grouping_currency_separator' => '.',
            ],
            'de-CH' => [
                'currency_pattern' => '¤ #,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;¤-#,##0.00',
                'grouping_separator' => '’',
            ],
            'de-LI' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'grouping_separator' => '’',
            ],
            'doi' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'dsb' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'dua' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'dv' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'dz' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0 %',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
            ],
            'ebu' => [],
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
            'en-CZ' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
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
            'en-ES' => [
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
            'en-FR' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'en-HU' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'en-ID' => [
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'en-IN' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤#,##,##0.00',
            ],
            'en-IT' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
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
            'en-NO' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'en-PL' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'en-PT' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'en-RO' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
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
            'en-SK' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'en-Shaw' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'es-AR' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;(¤ #,##0.00)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-BO' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-CL' => [
                'currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-CO' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-CR' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'es-DO' => [],
            'es-EC' => [
                'currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-GQ' => [
                'percent_pattern' => '#,##0 %',
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-PE' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'es-PY' => [
                'currency_pattern' => '¤ #,##0.00;¤ -#,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;¤ -#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-UY' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;(¤ #,##0.00)',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'es-VE' => [
                'currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00;¤-#,##0.00',
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
            'ewo' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
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
            'gaa' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'gd' => [],
            'gez' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
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
            'guz' => [],
            'haw' => [],
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
            'hsb' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'ht' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
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
            'ie' => [
                'currency_pattern' => '¤ #,##0.00;¤ -#,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00;¤ -#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'ig' => [],
            'ii' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
                'accounting_currency_pattern' => '¤ #,##0.00;¤-#,##0.00',
                'grouping_separator' => '’',
            ],
            'iu' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'iu-Latn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ja' => [],
            'jbo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'jgo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'jmc' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'ka' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'kaa' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'kab' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'kaj' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'kam' => [],
            'kcg' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'kde' => [],
            'kea' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'ken' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'kgp' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'khq' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'grouping_separator' => ' ',
            ],
            'ki' => [],
            'kk' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'kk-Arab' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'kkj' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'kl' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00;¤-#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'kln' => [],
            'km' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤;(#,##0.00¤)',
            ],
            'ko' => [],
            'kok' => [
                'currency_pattern' => '¤ #,##0.00',
            ],
            'kok-Latn' => [
                'decimal_pattern' => '#,##,##0.###',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
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
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ksb' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
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
            'kw' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'kxv' => [
                'decimal_pattern' => '#,##,##0.###',
                'currency_pattern' => '¤#,##,##0.00',
            ],
            'kxv-Deva' => [
                'decimal_pattern' => '#,##,##0.###',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
            ],
            'kxv-Orya' => [
                'decimal_pattern' => '#,##,##0.###',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
            ],
            'kxv-Telu' => [
                'decimal_pattern' => '#,##,##0.###',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
            ],
            'ky' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'la' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'lag' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'lij' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'lkt' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'lld' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'lmo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '’',
            ],
            'ln' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
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
            'ltg' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'mai' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mas' => [],
            'mdf' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mer' => [],
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
            'mgo' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mhn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mi' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mic' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mk' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'mn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mn-Mong' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'mn-Mong-MN' => [
                'accounting_currency_pattern' => '¤#,##0.00',
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
            'ms-Arab' => [],
            'ms-Arab-BN' => [
                'currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
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
            'mua' => [
                'decimal_separator' => ',',
                'grouping_separator' => '.',
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
            'nd' => [],
            'nds' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
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
            'nmg' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'nnh' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'no' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤;-#,##0.00 ¤',
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
            'nr' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'nso' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤#,##0.00',
                'grouping_separator' => ' ',
            ],
            'nus' => [],
            'nv' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'ny' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'nyn' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'oc' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'om' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'or' => [
                'decimal_pattern' => '#,##,##0.###',
            ],
            'os' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
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
            'pcm' => [
                'accounting_currency_pattern' => '¤#,##0.00',
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
            'rwk' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
            ],
            'sa' => [
                'numbering_system' => 'deva',
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'sah' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'saq' => [],
            'sbp' => [
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
            ],
            'sc' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'scn' => [
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
            'shn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'si' => [],
            'sid' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'sk' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤;(#,##0.00 ¤)',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'skr' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            'smn' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'sms' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'sn' => [],
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
            ],
            'su' => [
                'accounting_currency_pattern' => '¤#,##0.00',
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
            'syr' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'szl' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
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
            'teo' => [],
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
            'tn' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'grouping_separator' => '’',
            ],
            'tok' => [
                'decimal_pattern' => '#,#0.###',
                'percent_pattern' => '%#,#0',
                'currency_pattern' => '¤#,#0.00',
                'accounting_currency_pattern' => '¤#,#0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
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
            'ts' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
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
            'tyv' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
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
            've' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'vec' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'vi' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'vmw' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'vun' => [
                'accounting_currency_pattern' => '¤#,##0.00',
            ],
            'wa' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'wae' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '’',
            ],
            'wal' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'wbp' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'xh' => [
                'accounting_currency_pattern' => '¤#,##0.00',
                'grouping_separator' => ' ',
            ],
            'xnr' => [
                'decimal_pattern' => '#,##,##0.###',
                'percent_pattern' => '#,##,##0%',
                'currency_pattern' => '¤#,##,##0.00',
                'accounting_currency_pattern' => '¤#,##,##0.00',
            ],
            'xog' => [
                'currency_pattern' => '#,##0.00 ¤',
                'accounting_currency_pattern' => '#,##0.00 ¤',
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
            'yrl' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
                'decimal_separator' => ',',
                'grouping_separator' => '.',
            ],
            'yue' => [],
            'yue-Hans' => [],
            'za' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'zgh' => [
                'percent_pattern' => '#,##0 %',
                'currency_pattern' => '#,##0.00¤',
                'accounting_currency_pattern' => '#,##0.00¤',
                'decimal_separator' => ',',
                'grouping_separator' => ' ',
            ],
            'zh' => [],
            'zh-Hant' => [],
            'zh-Latn' => [
                'currency_pattern' => '¤ #,##0.00',
                'accounting_currency_pattern' => '¤ #,##0.00',
            ],
            'zu' => [],
        ];
    }
}
