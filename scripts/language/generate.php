<?php

/**
 * Generates the json files stored in resources/language.
 *
 * CLDR lists about 515 languages, many of them dead (like Latin or Old English).
 * In order to decrease the list to a reasonable size, only the languages
 * for which CLDR itself has translations are listed.
 */

set_time_limit(0);

// Downloaded from http://unicode.org/Public/cldr/26/json-full.zip
$enLanguages = '../json-full/main/en/languages.json';
if (!file_exists($enLanguages)) {
    die("The $enLanguages file was not found");
}
if (!function_exists('collator_create')) {
    // Reimplementing intl's collator would be a huge undertaking, so we
    // use it instead to presort the generated locale specific data.
    die('The intl extension was not found.');
}

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

$languages = array();
// Load the "en" data first so that it can be used as a fallback for
// untranslated language names in other locales.
$languageData = json_decode(file_get_contents($enLanguages), true);
$languageData = $languageData['main']['en']['localeDisplayNames']['languages'];
foreach ($languageData as $languageCode => $languageName) {
    if (strpos($languageCode, '-alt-') === FALSE) {
        $languages['en'][$languageCode] = array(
            'code' => $languageCode,
            'name' => $languageName,
        );
    }
}

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

// Remove all languages that aren't an available locale at the same time.
// This reduces the language list from about 515 to about 185 languages.
foreach ($languages['en'] as $languageCode => $languageData) {
    if (!in_array($languageCode, $locales)) {
        unset($languages['en'][$languageCode]);
    }
}

// Load the localizations.
foreach ($locales as $locale) {
    $data = json_decode(file_get_contents('../json-full/main/' . $locale . '/languages.json'), true);
    $data = $data['main'][$locale]['localeDisplayNames']['languages'];
    foreach ($data as $languageCode => $languageName) {
        if (isset($languages['en'][$languageCode])) {
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
    $collator = collator_create($locale);
    uasort($localizedLanguages, function($a, $b) use ($collator) {
        return collator_compare($collator, $a['name'], $b['name']);
    });

    $json = json_encode($localizedLanguages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($locale . '.json', $json);
}
