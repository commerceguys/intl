<?php

/**
 * Generates the json files stored in resources/number_format.
 */

set_time_limit(0);
require __DIR__ . '/generate_base.php';

$enLanguages = $localeDirectory . 'en/languages.json';
if (!file_exists($enLanguages)) {
    die("The $enLanguages file was not found");
}

$numberFormats = generate_number_formats();
$numberFormats = filter_duplicates($numberFormats);
// We treat 'en' as a generic definition, which allows
// us to strip any data that matches one of its keys.
foreach ($numberFormats as $locale => $numberFormat) {
    if ($locale != 'en') {
        $numberFormats[$locale] = array_diff_assoc($numberFormats[$locale], $numberFormats['en']);
    }
}

// Number formats are stored in PHP, then manually
// transferred to NumberFormatRepository.
$data = "<?php\n\n";
$data .= export_number_formats($numberFormats);
file_put_contents(__DIR__ . '/number_formats.php', $data);

echo "Done.\n";

/**
 * Exports number formats.
 */
function export_number_formats(array $numberFormats)
{
    $indent = '    ';
    $export = '// ' . count($numberFormats) . " available formats: \n";
    $export .= '$numberFormats = [' . "\n";
    foreach ($numberFormats as $locale => $numberFormat) {
        $locale = "'" . $locale . "'";
        $export .= $indent . $locale . " => [\n";
        foreach ($numberFormat as $key => $value) {
            $key = "'" . $key . "'";
            $value = "'" . $value . "'";
            $export .= $indent . $indent . $key . ' => ' . $value . ",\n";
        }
        $export .= "$indent],\n";
    }
    $export .= '];' . "\n\n";
    $export = str_replace("[\n$indent],", '[],', $export);

    return $export;
}

/**
 * Generates the number formats.
 */
function generate_number_formats()
{
    global $numbersDirectory;

    $numberFormats = [];
    foreach (discover_locales() as $locale) {
        $data = json_decode(file_get_contents($numbersDirectory . $locale . '/numbers.json'), true);
        $data = $data['main'][$locale]['numbers'];
        // Use the default numbering system, if it's supported.
        if (in_array($data['defaultNumberingSystem'], ['arab', 'arabext', 'beng', 'deva', 'latn'])) {
            $numberingSystem = $data['defaultNumberingSystem'];
        } else {
            $numberingSystem = 'latn';
        }

        $patterns = [
            'decimal' => $data['decimalFormats-numberSystem-' . $numberingSystem]['standard'],
            'percent' =>  $data['percentFormats-numberSystem-' . $numberingSystem]['standard'],
            'currency' => $data['currencyFormats-numberSystem-' . $numberingSystem]['standard'],
            'accounting' => $data['currencyFormats-numberSystem-' . $numberingSystem]['accounting'],
        ];
        // The "bg" patterns have no '#', confusing the formatter.
        foreach ($patterns as $key => $pattern) {
            if (strpos($pattern, '#') === false) {
                $patterns[$key] = str_replace('0.00', '#0.00', $pattern);
            }
        }

        $numberFormats[$locale] = [
            'numbering_system' => $numberingSystem,
            'decimal_pattern' => $patterns['decimal'],
            'percent_pattern' => $patterns['percent'],
            'currency_pattern' => $patterns['currency'],
            'accounting_currency_pattern' => $patterns['accounting'],
        ];
        // No need to export 'latn' since that is the default value.
        if ($numberFormats[$locale]['numbering_system'] != 'latn') {
            $numberFormats[$locale]['numbering_system'] = $numberingSystem;
        }

        // Add the symbols only if they're different from the default data.
        $decimalSeparator = $data['symbols-numberSystem-' . $numberingSystem]['decimal'];
        $groupingSeparator = $data['symbols-numberSystem-' . $numberingSystem]['group'];
        $plusSign = $data['symbols-numberSystem-' . $numberingSystem]['plusSign'];
        $minusSign = $data['symbols-numberSystem-' . $numberingSystem]['minusSign'];
        $percentSign = $data['symbols-numberSystem-' . $numberingSystem]['percentSign'];
        if ($decimalSeparator != '.') {
            $numberFormats[$locale]['decimal_separator'] = $decimalSeparator;
        }
        if ($groupingSeparator != ',') {
            $numberFormats[$locale]['grouping_separator'] = $groupingSeparator;
        }
        if ($plusSign != '+') {
            $numberFormats[$locale]['plus_sign'] = $plusSign;
        }
        if ($minusSign != '-') {
            $numberFormats[$locale]['minus_sign'] = $minusSign;
        }
        if ($percentSign != '%') {
            $numberFormats[$locale]['percent_sign'] = $percentSign;
        }
    }
    ksort($numberFormats);

    return $numberFormats;
}

/**
 * Filters out duplicate number formats (same as their parent locale).
 *
 * For example, "fr-FR" will be removed if "fr" has the same data.
 */
function filter_duplicates(array $numberFormats)
{
    $duplicates = [];
    foreach ($numberFormats as $locale => $numberFormat) {
        $parentNumberFormat = [];
        $parentLocale = \CommerceGuys\Intl\Locale::getParent($locale);
        if ($parentLocale && isset($numberFormats[$parentLocale])) {
            $parentNumberFormat = $numberFormats[$parentLocale];
        }

        $diff = array_diff_assoc($numberFormat, $parentNumberFormat);
        if (empty($diff)) {
            // The duplicates are not removed right away because they might
            // still be needed for other duplicate checks (for example,
            // when there are locales like bs-Latn-BA, bs-Latn, bs).
            $duplicates[] = $locale;
        }
    }
    // Remove the duplicates.
    foreach ($duplicates as $locale) {
        unset($numberFormats[$locale]);
    }

    return $numberFormats;
}
