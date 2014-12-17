<?php

/**
 * Generates the json files stored in resources/country.
 */

set_time_limit(0);

// Downloaded from http://unicode.org/Public/cldr/26/json-full.zip
$enCountries = '../json-full/main/en/territories.json';
$codeMappings = '../json-full/supplemental/codeMappings.json';
$telephoneCodeData = '../json-full/supplemental/telephoneCodeData.json';
if (!file_exists($enCountries)) {
    die("The $enCountries file was not found");
}
if (!file_exists($codeMappings)) {
    die("The $codeMappings file was not found");
}
if (!file_exists($telephoneCodeData)) {
    die("The $telephoneCodeData file was not found");
}
if (!function_exists('collator_create')) {
    // Reimplementing intl's collator would be a huge undertaking, so we
    // use it instead to presort the generated locale specific data.
    die('The intl extension was not found.');
}

$ignoredCountries = array(
    'AN', // Netherlands Antilles, no longer exists.
    'BV', 'HM', 'CP', // Uninhabited islands.
    'EU', 'QO', // European Union, Outlying Oceania. Not countries.
    'ZZ', // Unknown region
);

// Locales listed without a "-" match all variants.
// Locales listed with a "-" match only those exact ones.
$ignoredLocales = array(
    // Interlingua is a made up language.
    'ia',
    // Valencian differs from its parent only by a single character (è/é).
    'ca-ES-VALENCIA',
    // Those locales are 90% untranslated.
    'aa', 'as', 'az-Cyrl', 'az-Cyrl-AZ', 'bem', 'dua', 'gv', 'haw', 'ig', 'ii',
    'kkj', 'kok', 'kw', 'lkt', 'mgo', 'nnh', 'nr', 'nso', 'om', 'os', 'pa-Arab',
    'pa-Arab-PK', 'qu', 'rw', 'sah', 'smn', 'ss', 'ssy', 'st', 'tg', 'tn', 'ts',
    'uz-Arab', 'uz-Arab-AF', 've', 'vo', 'xh', 'yi',
    // Special "grouping" locales.
    'root', 'en-US-POSIX', 'en-001', 'en-150', 'es-419',
);

// Assemble the base data. Use the "en" data to get a list of countries.
$telephoneCodeData = json_decode(file_get_contents($telephoneCodeData), true);
$telephoneCodeData = $telephoneCodeData['supplemental']['telephoneCodeData'];
$codeMappings = json_decode(file_get_contents($codeMappings), true);
$codeMappings = $codeMappings['supplemental']['codeMappings'];
$countryData = json_decode(file_get_contents($enCountries), true);
$countryData = $countryData['main']['en']['localeDisplayNames']['territories'];
$baseData = array();
foreach ($countryData as $countryCode => $countryName) {
    if (is_numeric($countryCode) || in_array($countryCode, $ignoredCountries)) {
        // Ignore continents, regions, uninhabited islands.
        continue;
    }
    if (strpos($countryCode, '-alt-') !== FALSE) {
        // Ignore alternative names.
        continue;
    }

    $baseData[$countryCode]['code'] = $countryCode;
    // Countries are not guaranteed to have an alpha3 and/or numeric code.
    if (isset($codeMappings[$countryCode]['_alpha3'])) {
        $baseData[$countryCode]['three_letter_code'] = $codeMappings[$countryCode]['_alpha3'];
    }
    if (isset($codeMappings[$countryCode]['_numeric'])) {
        $baseData[$countryCode]['numeric_code'] = $codeMappings[$countryCode]['_numeric'];
    }

    // Determine the telephone code for this country.
    if (in_array($countryCode, array('IC', 'EA'))) {
        // "Canary Islands" and "Ceuta and Melilla" use Spain's.
        $baseData[$countryCode]['telephone_code'] = $telephoneCodeData['ES'][0]['telephoneCountryCode'];
    } elseif ($countryCode == 'XK') {
        // Kosovo uses three telephone codes. Use Serbia's until that gets resolved.
        $baseData[$countryCode]['telephone_code'] = $telephoneCodeData['RS'][0]['telephoneCountryCode'];
    } elseif (isset($telephoneCodeData[$countryCode])) {
        $baseData[$countryCode]['telephone_code'] = $telephoneCodeData[$countryCode][0]['telephoneCountryCode'];
    }
}

// Write out base.json.
ksort($baseData);
$json = json_encode($baseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents('base.json', $json);

// Gather available locales.
$locales = array();
if ($handle = opendir('../json-full/main')) {
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
$countries = array();
foreach ($locales as $locale) {
    $data = json_decode(file_get_contents('../json-full/main/' . $locale . '/territories.json'), true);
    $data = $data['main'][$locale]['localeDisplayNames']['territories'];
    foreach ($data as $countryCode => $countryName) {
        if (isset($baseData[$countryCode])) {
            // This country name is untranslated, use the english version.
            if ($countryCode == $countryName) {
                $countryName = $countryData[$countryCode];
            }

            $countries[$locale][$countryCode] = array(
                'name' => $countryName,
            );
        }
    }
}

// Identify localizations that are the same as the ones for the parent locale.
// For example, "fr-FR" if "fr" has the same data.
$duplicates = array();
foreach ($countries as $locale => $localizedCountries) {
    if (strpos($locale, '-') !== FALSE) {
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
    uasort($localizedCountries, function($a, $b) use ($collator) {
        return collator_compare($collator, $a['name'], $b['name']);
    });

    $json = json_encode($localizedCountries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($locale . '.json', $json);
}
