<?php

/**
 * Generates the yml files stored in resources/country.
 */

set_time_limit(0);

include '../../vendor/autoload.php';

use Symfony\Component\Yaml\Dumper;

$dumper = new Dumper;

// Downloaded from http://unicode.org/Public/cldr/25/json_full.zip
$enCountries = '../json_full/main/en/territories.json';
$codeMappings = '../json_full/supplemental/codeMappings.json';
if (!file_exists($enCountries)) {
    die("The $enCountries file was not found");
}
if (!file_exists($codeMappings)) {
    die("The $codeMappings file was not found");
}

$ignoredCountries = array(
    'AN', // Netherlands Antilles, no longer exists.
    'BV', 'HM', 'CP', // Uninhabited islands.
    'EU', 'QO', // European Union, Outlying Oceania. Not countries.
    'ZZ', // Unknown region
);

// Assemble the base data. Use the "en" data to get a list of countries.
$codeMappings = json_decode(file_get_contents($codeMappings), TRUE);
$codeMappings = $codeMappings['supplemental']['codeMappings'];
$countryData = json_decode(file_get_contents($enCountries), TRUE);
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
}

// Write out base.yml.
ksort($baseData);
$yaml = $dumper->dump($baseData, 3);
file_put_contents('base.yml', $yaml);

// Gather available locales.
$locales = array();
if ($handle = opendir('../json_full/main')) {
    while (false !== ($entry = readdir($handle))) {
        if (substr($entry, 0, 1) != '.' && !in_array($entry, array('en-US-POSIX', 'en-001', 'en-150'))) {
          $locales[] = $entry;
        }
    }
    closedir($handle);
}

// Create the localizations.
$countries = array();
foreach ($locales as $locale) {
    $data = json_decode(file_get_contents('../json_full/main/' . $locale . '/territories.json'), TRUE);
    $data = $data['main'][$locale]['localeDisplayNames']['territories'];
    foreach ($data as $countryCode => $countryName) {
        if (isset($baseData[$countryCode])) {
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
    ksort($localizedCountries);
    $yaml = $dumper->dump($localizedCountries, 3);
    file_put_contents($locale . '.yml', $yaml);
}
