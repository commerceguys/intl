<?php

/**
 * Generates the yml files stored in resources/language.
 */

set_time_limit(0);

include '../../vendor/autoload.php';

use Symfony\Component\Yaml\Dumper;

$dumper = new Dumper;

// Downloaded from http://unicode.org/Public/cldr/25/json_full.zip
$enLanguages = '../json_full/main/en/languages.json';
if (!file_exists($enLanguages)) {
    die("The $enLanguages file was not found");
}

$languages = array();
// Load the "en" data first so that it can be used as a fallback for
// untranslated language names in other locales.
$languageData = json_decode(file_get_contents($enLanguages), TRUE);
$languageData = $languageData['main']['en']['localeDisplayNames']['languages'];
foreach ($languageData as $languageCode => $languageName) {
    if (in_array($languageCode, array('und', 'zxx'))) {
        // Ignore "Unknown language" and "No linguistic content"
        continue;
    }
    if (strpos($languageCode, '-alt-') !== FALSE) {
        // Ignore alternative names.
        continue;
    }
    $languages['en'][$languageCode] = array(
        'code' => $languageCode,
        'name' => $languageName,
    );
}

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

// Load the localizations.
foreach ($locales as $locale) {
    $data = json_decode(file_get_contents('../json_full/main/' . $locale . '/languages.json'), TRUE);
    $data = $data['main'][$locale]['localeDisplayNames']['languages'];
    foreach ($data as $languageCode => $languageName) {
        if (in_array($languageCode, array('und', 'zxx'))) {
            // Ignore "Unknown language" and "No linguistic content"
            continue;
        }
        if (strpos($languageCode, '-alt-') !== FALSE) {
            // Ignore alternative names.
            continue;
        }

        // This language name is untranslated, use to the english version.
        if ($languageCode == $languageName) {
            $languageName = $languages['en'][$languageCode]['name'];
        }

        $languages[$locale][$languageCode] = array(
            'code' => $languageCode,
            'name' => $languageName,
        );
    }
}

// Identify localizations that are the same as the ones for the parent locale.
// For example, "fr-FR" if "fr" has the same data.
$duplicates = array();
foreach ($languages as $locale => $localizedLanguages) {
    if (strpos($locale, '-') !== FALSE) {
        $localeParts = explode('-', $locale);
        array_pop($localeParts);
        $parentLocale = implode('-', $localeParts);
        $diff = array_udiff($localizedLanguages, $languages[$parentLocale], function ($first, $second) {
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
    unset($languages[$locale]);
}

// Write out the localizations.
foreach ($languages as $locale => $localizedLanguages) {
    ksort($localizedLanguages);
    $yaml = $dumper->dump($localizedLanguages, 3);
    file_put_contents($locale . '.yml', $yaml);
}
