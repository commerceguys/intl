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

// Assemble the base data. Use the "en" data to get a list of countries.
$codeMappings = json_decode(file_get_contents($codeMappings), TRUE);
$codeMappings = $codeMappings['supplemental']['codeMappings'];
$countryData = json_decode(file_get_contents($enCountries), TRUE);
$countryData = $countryData['main']['en']['localeDisplayNames']['territories'];
$baseData = array();
foreach ($countryData as $countryCode => $countryName) {
    if (is_numeric($countryCode) || in_array($countryCode, array('EU', 'ZZ'))) {
        // Ignore territories that aren't countries (continents, EU).
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
        if (substr($entry, 0, 1) != '.' && !in_array($entry, array('en-001', 'en-150'))) {
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
        if (is_numeric($countryCode) || in_array($countryCode, array('EU', 'ZZ'))) {
            // Ignore territories that aren't countries (continents, EU).
            continue;
        }
        if (strpos($countryCode, '-alt-') !== FALSE) {
            // Ignore alternative names.
            continue;
        }

        $countries[$locale][$countryCode] = array(
            'name' => $countryName,
        );
    }
}

// Remove localizations that are the same as the ones for the parent locale.
// For example, don't provide "fr-FR" if "fr" has the same data.
foreach ($countries as $locale => $localizedCountries) {
    if (strpos($locale, '-') !== FALSE) {
        $localeParts = explode('-', $locale);
        $parentTranslations = $countries[$localeParts[0]];
        $diff = array_udiff($localizedCountries, $parentTranslations, function ($first, $second) {
            return ($first['name'] == $second['name']) ? 0 : 1;
        });

        if (empty($diff)) {
            unset($countries[$locale]);
        }
    }
}

// Write out the localizations.
foreach ($countries as $locale => $localizedCountries) {
    ksort($localizedCountries);
    $yaml = $dumper->dump($localizedCountries, 3);
    file_put_contents($locale . '.yml', $yaml);
}
