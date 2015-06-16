<?php

/**
 * Generates the json files stored in resources/number_format.
 */
set_time_limit(0);

// Downloaded from https://github.com/unicode-cldr/cldr-localenames-full.git
$localeDirectory = '../assets/cldr-localenames-full/main/';
$enLanguages = $localeDirectory . 'en/languages.json';
// Downloaded from https://github.com/unicode-cldr/cldr-numbers-full.git
$numbersDirectory = '../assets/cldr-numbers-full/main/';

if (!is_dir($localeDirectory)) {
    die("The $localeDirectory directory was not found");
}
if (!is_dir($numbersDirectory)) {
    die("The $numbersDirectory directory was not found");
}
if (!file_exists($enLanguages)) {
    die("The $enLanguages file was not found");
}

// Locales listed without a "-" match all variants.
// Locales listed with a "-" match only those exact ones.
$ignoredLocales = [
    // Interlingua is a made up language.
    'ia',
    // Ignored by other generation scripts, very minor locales.
    'as', 'asa', 'bem', 'chr', 'dav', 'dua', 'ebu', 'ewo', 'guz', 'gv', 'ii',
    'jgo', 'jmc', 'kam', 'kde', 'ki', 'kkj', 'kl', 'kln', 'ksb', 'kw', 'lag',
    'ln', 'mer', 'mgo', 'nd', 'nmg', 'nnh', 'nus', 'os', 'ps', 'rwk', 'sah',
    'saq', 'sbp', 'shi', 'sn', 'teo', 'vai', 'vun', 'xog', 'zgh',
    // Special "grouping" locales.
    'root', 'en-US-POSIX', 'en-001', 'en-150', 'es-419',
];

// Gather available locales.
$locales = [];
if ($handle = opendir($localeDirectory)) {
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
$numberFormats = [];
foreach ($locales as $locale) {
    $data = json_decode(file_get_contents($numbersDirectory . $locale . '/numbers.json'), true);
    $data = $data['main'][$locale]['numbers'];
    // Use the default numbering system, if it's supported.
    if (in_array($data['defaultNumberingSystem'], ['arab', 'arabext', 'beng', 'deva', 'latn'])) {
        $numberingSystem = $data['defaultNumberingSystem'];
    } else {
        $numberingSystem = 'latn';
    }

    $numberFormats[$locale] = [
        'numbering_system' => $numberingSystem,
        'decimal_pattern' => $data['decimalFormats-numberSystem-' . $numberingSystem]['standard'],
        'percent_pattern' => $data['percentFormats-numberSystem-' . $numberingSystem]['standard'],
        'currency_pattern' => $data['currencyFormats-numberSystem-' . $numberingSystem]['standard'],
        'accounting_currency_pattern' => $data['currencyFormats-numberSystem-' . $numberingSystem]['accounting'],
    ];

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
$duplicates = [];
foreach ($numberFormats as $locale => $formatData) {
    if (strpos($locale, '-') !== false) {
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
    file_put_json($locale . '.json', $numberFormat);
}

/**
 * Converts the provided data into json and writes it to the disk.
 */
function file_put_json($filename, $data)
{
    $data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    // Indenting with tabs instead of 4 spaces gives us 20% smaller files.
    $data = str_replace('    ', "\t", $data);
    file_put_contents($filename, $data);
}
