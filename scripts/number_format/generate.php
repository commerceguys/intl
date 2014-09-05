<?php

/**
 * Generates the yml files stored in resources/number_format.
 */

set_time_limit(0);

include '../../vendor/autoload.php';

use Symfony\Component\Yaml\Dumper;

$dumper = new Dumper();

// Downloaded from http://unicode.org/Public/cldr/25/json_full.zip
if (!is_dir('../json_full/main')) {
    die("The '../json_full/main' directory was not found");
}

// Locales listed without a "-" match all variants.
// Locales listed with a "-" match only those exact ones.
$ignoredLocales = array(
    // Interlingua is a made up language.
    'ia',
    // Special "grouping" locales.
    'root', 'en-US-POSIX', 'en-001', 'en-150',
);

// Gather available locales.
$locales = array();
if ($handle = opendir('../json_full/main')) {
    while (false !== ($entry = readdir($handle))) {
        if (substr($entry, 0, 1) != '.') {
            $entryParts = explode('-', $entry);
            if (!in_array($entry, $ignoredLocales) && !in_array($entryParts[0], $ignoredLocales)) {
                $locales[] = $entry;
            }
        }
    }
    closedir($handle);
}

// Load the data.
$numberFormats = array();
foreach ($locales as $locale) {
    $data = json_decode(file_get_contents('../json_full/main/' . $locale . '/numbers.json'), true);
    $data = $data['main'][$locale]['numbers'];
    // Use the default numbering system, if it's supported.
    if (in_array($data['defaultNumberingSystem'], array('arab', 'arabext', 'beng', 'deva', 'latn'))) {
        $numberingSystem = $data['defaultNumberingSystem'];
    } else {
        $numberingSystem = 'latn';
    }

    $numberFormats[$locale] = array(
        'numbering_system' => $numberingSystem,
        'decimal_pattern' => $data['decimalFormats-numberSystem-' . $numberingSystem]['standard'],
        'percent_pattern' => $data['percentFormats-numberSystem-' . $numberingSystem]['standard'],
        'currency_pattern' => $data['currencyFormats-numberSystem-' . $numberingSystem]['standard'],
        'accounting_currency_pattern' => $data['currencyFormats-numberSystem-' . $numberingSystem]['accounting'],
    );

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

// Identify localizations that are the same as the ones for the parent locale.
// For example, "fr-FR" if "fr" has the same data.
$duplicates = array();
foreach ($numberFormats as $locale => $formatData) {
    if (strpos($locale, '-') !== FALSE) {
        $localeParts = explode('-', $locale);
        array_pop($localeParts);
        $parentLocale = implode('-', $localeParts);
        $diff = array_diff_assoc($formatData, $numberFormats[$parentLocale]);

        if (empty($diff)) {
            // The duplicates are not removed right away because they might
            // still be needed for other duplicate checks (for example,
            // when there are locales like bs-Latn-BA, bs-Latn, bs).
            $duplicates[] = $locale;
        }
    }
}
// Remove the duplicates.
foreach ($duplicates as $locale) {
    unset($numberFormats[$locale]);
}

// Write out the data.
foreach ($numberFormats as $locale => $numberFormat) {
    $yaml = $dumper->dump($numberFormat, 3);
    file_put_contents($locale . '.yml', $yaml);
}
