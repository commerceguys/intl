<?php

/**
 * Generates the json files stored in resources/language.
 *
 * CLDR lists about 515 languages, many of them dead (like Latin or Old English).
 * In order to decrease the list to a reasonable size, only the languages
 * for which CLDR itself has translations are listed.
 */

set_time_limit(0);
require __DIR__ . '/../vendor/autoload.php';

// Downloaded from https://github.com/unicode-cldr/cldr-localenames-full.git
$localeDirectory = __DIR__ . '/assets/cldr-localenames-full/main/';
$enLanguages = $localeDirectory . 'en/languages.json';

if (!is_dir($localeDirectory)) {
    die("The $localeDirectory directory was not found");
}
if (!file_exists($enLanguages)) {
    die("The $enLanguages file was not found");
}
if (!function_exists('collator_create')) {
    // Reimplementing intl's collator would be a huge undertaking, so we
    // use it instead to presort the generated locale specific data.
    die('The intl extension was not found.');
}

$languages = generate_languages();
$languages = filter_duplicate_localizations($languages);

// Make sure we're starting from a clean slate.
if (is_dir(__DIR__ . '/language')) {
    die('The language/ directory must not exist.');
}

// Prepare the filesystem.
mkdir(__DIR__ . '/language');

// Write out the localizations.
foreach ($languages as $locale => $localizedLanguages) {
    $collator = collator_create($locale);
    uasort($localizedLanguages, function ($a, $b) use ($collator) {
        return collator_compare($collator, $a, $b);
    });
    file_put_json(__DIR__ . '/language/' . $locale . '.json', $localizedLanguages);
}

$availableLocales = array_keys($languages);
sort($availableLocales);
// Available locales are stored in PHP, then manually
// transferred to LanguageRepository.
$data = "<?php\n\n";
$data .= export_locales($availableLocales);
file_put_contents(__DIR__ . '/language_data.php', $data);

echo "Done.\n";

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
 * Exports locales.
 */
function export_locales($data)
{
    // Wrap the values in single quotes.
    $data = array_map(function ($value) {
        return "'" . $value . "'";
    }, $data);

    $export = '// ' . count($data) . " available locales. \n";
    $export .= '$locales = [' . "\n";
    $export .= '    ' . implode(', ', $data) . "\n";
    $export .= "];\n";

    return $export;
}

/**
 * Generates the language lists for each locale.
 */
function generate_languages()
{
    global $localeDirectory;

    $locales = discover_locales();
    // Make sure 'en' is processed first so that it can be used as a fallback.
    $index = array_search('en', $locales);
    unset($locales[$index]);
    array_unshift($locales, 'en');
    // The filtering of the language list against the locale list can be
    // too strict, filtering out languages that should be in the final list.
    // This override ensures that such cases are covered.
    $explicitlyAllowed = ['wa'];
    // Languages that are untranslated in most locales (as of CLDR v34).
    $explicitlyIgnored = ['ccp', 'fa-AF'];

    $untranslatedCounts = [];
    $languages = [];
    foreach ($locales as $locale) {
        $data = json_decode(file_get_contents($localeDirectory . $locale . '/languages.json'), true);
        $data = $data['main'][$locale]['localeDisplayNames']['languages'];
        foreach ($data as $languageCode => $languageName) {
            // Skip all languages that aren't an available locale at the same time.
            // This reduces the language list from about 515 to about 185 languages.
            if (!in_array($languageCode, $locales) && !in_array($languageCode, $explicitlyAllowed)) {
                continue;
            }
            if (in_array($languageCode, $explicitlyIgnored)) {
                continue;
            }

            // This language name is untranslated, use to the english version.
            if ($languageCode == str_replace('_', '-', $languageName)) {
                $languageName = $languages['en'][$languageCode];
                // Maintain a count of untranslated languages per locale.
                $untranslatedCounts += [$locale => 0];
                $untranslatedCounts[$locale]++;
            }

            $languages[$locale][$languageCode] = $languageName;
        }
        // CLDR v34 has an uneven language list due to missing translations.
        if ($locale != 'en') {
            $missingLanguages = array_diff_key($languages['en'], $languages[$locale]);
            foreach ($missingLanguages as $languageCode => $languageName) {
                $languages[$locale][$languageCode] = $languages['en'][$languageCode];
            }
        }
    }

    // Ignore locales that are more than 80% untranslated.
    foreach ($untranslatedCounts as $locale => $count) {
        $totalCount = count($languages[$locale]);
        $untranslatedPercentage = $count * (100 / $totalCount);
        if ($untranslatedPercentage >= 80) {
            unset($languages[$locale]);
        }
    }

    return $languages;
}

/**
 * Filters out duplicate localizations (same as their parent locale).
 *
 * For example, "fr-FR" will be removed if "fr" has the same data.
 */
function filter_duplicate_localizations(array $localizations)
{
    $duplicates = [];
    foreach ($localizations as $locale => $localizedLanguages) {
        if ($parentLocale = \CommerceGuys\Intl\Locale::getParent($locale)) {
            $parentLanguages = isset($localizations[$parentLocale]) ? $localizations[$parentLocale] : [];
            $diff = array_udiff($localizedLanguages, $parentLanguages, function ($first, $second) {
                return ($first === $second) ? 0 : 1;
            });

            if (empty($diff)) {
                // The duplicates are not removed right away because they might
                // still be needed for other duplicate checks (for example,
                // when there are locales like bs-Latn-BA, bs-Latn, bs).
                $duplicates[] = $locale;
            }
        }
    }
    foreach ($duplicates as $locale) {
        unset($localizations[$locale]);
    }

    return $localizations;
}

/**
 * Creates a list of available locales.
 */
function discover_locales()
{
    global $localeDirectory;

    // Locales listed without a "-" match all variants.
    // Locales listed with a "-" match only those exact ones.
    $ignoredLocales = [
        // Interlingua is a made up language.
        'ia',
        // Valencian differs from its parent only by a single character (è/é).
        'ca-ES-VALENCIA',
        // Special "grouping" locales.
        'root', 'en-US-POSIX',
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

    return $locales;
}
