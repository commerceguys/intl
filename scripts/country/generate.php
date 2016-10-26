<?php

/**
 * Generates the json files stored in resources/country.
 */
set_time_limit(0);

// Downloaded from https://github.com/unicode-cldr/cldr-localenames-full.git
$localeDirectory = '../assets/cldr-localenames-full/main/';
$enCountries = $localeDirectory . 'en/territories.json';
// Downloaded from https://github.com/unicode-cldr/cldr-core.git
$codeMappings = '../assets/cldr-core/supplemental/codeMappings.json';
$currencyData = '../assets/cldr-core/supplemental/currencyData.json';
if (!file_exists($enCountries)) {
    die("The $enCountries file was not found");
}
if (!file_exists($codeMappings)) {
    die("The $codeMappings file was not found");
}
if (!file_exists($currencyData)) {
    die("The $currencyData file was not found");
}
if (!function_exists('collator_create')) {
    // Reimplementing intl's collator would be a huge undertaking, so we
    // use it instead to presort the generated locale specific data.
    die('The intl extension was not found.');
}
if (!is_dir($localeDirectory)) {
    die("The $localeDirectory directory was not found");
}

$ignoredCountries = [
    'AN', // Netherlands Antilles, no longer exists.
    'BV', 'HM', 'CP', // Uninhabited islands.
    'EU', 'QO', // European Union, Outlying Oceania. Not countries.
    'ZZ', // Unknown region
];

// Locales listed without a "-" match all variants.
// Locales listed with a "-" match only those exact ones.
$ignoredLocales = [
    // Interlingua is a made up language.
    'ia',
    // Valencian differs from its parent only by a single character (è/é).
    'ca-ES-VALENCIA',
    // Special "grouping" locales.
    'root', 'en-US-POSIX', 'en-001', 'en-150', 'es-419',
];

// Assemble the base data. Use the "en" data to get a list of countries.
$codeMappings = json_decode(file_get_contents($codeMappings), true);
$codeMappings = $codeMappings['supplemental']['codeMappings'];
$currencyData = json_decode(file_get_contents($currencyData), true);
$currencyData = $currencyData['supplemental']['currencyData'];
$countryData = json_decode(file_get_contents($enCountries), true);
$countryData = $countryData['main']['en']['localeDisplayNames']['territories'];
$baseData = [];
foreach ($countryData as $countryCode => $countryName) {
    if (is_numeric($countryCode) || in_array($countryCode, $ignoredCountries)) {
        // Ignore continents, regions, uninhabited islands.
        continue;
    }
    if (strpos($countryCode, '-alt-') !== false) {
        // Ignore alternative names.
        continue;
    }

    // Countries are not guaranteed to have an alpha3 and/or numeric code.
    if (isset($codeMappings[$countryCode]['_alpha3'])) {
        $baseData[$countryCode]['three_letter_code'] = $codeMappings[$countryCode]['_alpha3'];
    }
    if (isset($codeMappings[$countryCode]['_numeric'])) {
        $baseData[$countryCode]['numeric_code'] = $codeMappings[$countryCode]['_numeric'];
    }

    // Determine the current currency for this country.
    if (isset($currencyData['region'][$countryCode])) {
        $currencies = prepare_currencies($currencyData['region'][$countryCode]);
        if ($currencies) {
            $currentCurrency = end(array_keys($currencies));
            $baseData[$countryCode]['currency_code'] = $currentCurrency;
        }
    }
}

// Write out base.json.
ksort($baseData);
file_put_json('base.json', $baseData);

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

// Create the localizations.
$countries = [];
$untranslatedCounts = [];
foreach ($locales as $locale) {
    $data = json_decode(file_get_contents($localeDirectory . $locale . '/territories.json'), true);
    $data = $data['main'][$locale]['localeDisplayNames']['territories'];
    foreach ($data as $countryCode => $countryName) {
        if (isset($baseData[$countryCode])) {
            // This country name is untranslated, use the english version.
            if ($countryCode == str_replace('_', '-', $countryName)) {
                $countryName = $countryData[$countryCode];
                // Maintain a count of untranslated countries per locale.
                $untranslatedCounts += [$locale => 0];
                $untranslatedCounts[$locale]++;
            }

            $countries[$locale][$countryCode] = [
                'name' => $countryName,
            ];
        }
    }
}

// Ignore locales that are more than 80% untranslated.
foreach ($untranslatedCounts as $locale => $count) {
    $totalCount = count($countries[$locale]);
    $untranslatedPercentage = $count * (100 / $totalCount);
    if ($untranslatedPercentage >= 80) {
        unset($countries[$locale]);
    }
}

// Identify localizations that are the same as the ones for the parent locale.
// For example, "fr-FR" if "fr" has the same data.
$duplicates = [];
foreach ($countries as $locale => $localizedCountries) {
    if (strpos($locale, '-') !== false) {
        $localeParts = explode('-', $locale);
        array_pop($localeParts);
        $parentLocale = implode('-', $localeParts);
        $diff = array_udiff($localizedCountries, $countries[$parentLocale], function ($first, $second) {
            return ($first['name'] == $second['name']) ? 0 : 1;
        });

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
    unset($countries[$locale]);
}

// Write out the localizations.
foreach ($countries as $locale => $localizedCountries) {
    $collator = collator_create($locale);
    uasort($localizedCountries, function ($a, $b) use ($collator) {
        return collator_compare($collator, $a['name'], $b['name']);
    });
    file_put_json($locale . '.json', $localizedCountries);
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

/**
 * Prepares the currencies for a specific country.
 */
function prepare_currencies($currencies) {
    if (empty($currencies)) {
        return [];
    }
    // Rekey the array by currency code.
    foreach ($currencies as $index => $realCurrencies) {
        foreach ($realCurrencies as $currencyCode => $currency) {
            $currencies[$currencyCode] = $currency;
        }
        unset($currencies[$index]);
    }
    // Remove non-tender currencies.
    $currencies = array_filter($currencies, function ($currency) {
        return !isset($currency['_tender']) || $currency['_tender'] != 'false';
    });
    // Sort by _from date.
    uasort($currencies, 'compare_from_dates');

    return $currencies;
}

/**
 * uasort callback for comparing arrays using their "_from" dates.
 */
function compare_from_dates($a, $b) {
    $a = new DateTime($a['_from']);
    $b = new DateTime($b['_from']);
    // DateTime overloads the comparison providers.
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}
