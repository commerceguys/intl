<?php

/**
 * Generates the json files stored in resources/currency.
 *
 * The ISO currency list is used as a base, since it doesn't contain
 * deprecated currencies, unlike CLDR (v25 has 139 deprecated entries).
 */

set_time_limit(0);
require __DIR__ . '/../vendor/autoload.php';

// Downloaded from http://www.currency-iso.org/en/home/tables/table-a1.html
$isoCurrencies = __DIR__ . '/assets/c2.xml';
// Downloaded from https://github.com/unicode-cldr/cldr-numbers-full.git
$numbersDirectory = __DIR__ . '/assets/cldr-numbers-full/main/';
$cldrCurrencies = $numbersDirectory . 'en/currencies.json';
// Downloaded from https://github.com/unicode-cldr/cldr-core.git
$currencyData = __DIR__ . '/assets/cldr-core/supplemental/currencyData.json';
// Downloaded from https://github.com/unicode-cldr/cldr-localenames-full.git
$localeDirectory = __DIR__ . '/assets/cldr-localenames-full/main/';
if (!file_exists($isoCurrencies)) {
    die("The $isoCurrencies file was not found");
}
if (!file_exists($cldrCurrencies)) {
    die("The $cldrCurrencies file was not found");
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
if (!is_dir($numbersDirectory)) {
    die("The $numbersDirectory directory was not found");
}

$currencyData = json_decode(file_get_contents($currencyData), true);
$isoData = simplexml_load_file($isoCurrencies);

$baseData = generate_base_data($currencyData, $isoData);
$localizations = generate_localizations($baseData);
$localizations = filter_duplicate_localizations($localizations);

// Make sure we're starting from a clean slate.
if (is_dir(__DIR__ . '/currency')) {
    die('The currency/ directory must not exist.');
}

// Prepare the filesystem.
mkdir(__DIR__ . '/currency');

// Write out the localizations.
foreach ($localizations as $locale => $localizedCurrencies) {
    $collator = collator_create($locale);
    uasort($localizedCurrencies, function ($a, $b) use ($collator) {
        return collator_compare($collator, $a['name'], $b['name']);
    });
    file_put_json(__DIR__ . '/currency/' . $locale . '.json', $localizedCurrencies);
}

$availableLocales = array_keys($localizations);
sort($availableLocales);
// Base currency definitions and available locales are stored
// in PHP, then manually transferred to CurrencyRepository.
$data = "<?php\n\n";
$data .= export_locales($availableLocales);
$data .= export_base_data($baseData);
file_put_contents(__DIR__ . '/currency_data.php', $data);

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
 * Exports base data.
 */
function export_base_data($baseData)
{
    $export = '$baseData = [' . "\n";
    foreach ($baseData as $currencyCode => $currencyData) {
        $export .= "    '" . $currencyCode . "' => ['";
        $export .= $currencyData['numeric_code'] . "', " . $currencyData['fraction_digits'];
        $export .= "],\n";
    }
    $export .= "];";

    return $export;
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
    $export .= "];\n\n";

    return $export;
}

/**
 * Generates the base data.
 */
function generate_base_data(array $currencyData, $isoData)
{
    $baseData = [];
    $currencyData = $currencyData['supplemental']['currencyData']['fractions'];
    foreach ($isoData->CcyTbl->CcyNtry as $currency) {
        $attributes = (array) $currency->CcyNm->attributes();
        if (!empty($attributes) && !empty($attributes['@attributes']['IsFund'])) {
            // Ignore funds.
            continue;
        }
        $currency = (array) $currency;
        if (empty($currency['Ccy'])) {
            // Ignore placeholders like "Antarctica".
            continue;
        }
        if (substr($currency['CtryNm'], 0, 2) == 'ZZ' || in_array($currency['Ccy'], ['XUA', 'XSU', 'XDR'])) {
            // Ignore special currencies.
            continue;
        }

        $currencyCode = $currency['Ccy'];
        $baseData[$currencyCode] = [
            'numeric_code' => $currency['CcyNbr'],
        ];
        // Take the fraction digits from CLDR, not ISO, because it reflects real
        // life usage more closely.
        if (isset($currencyData[$currencyCode]['_digits'])) {
            $baseData[$currencyCode]['fraction_digits'] = $currencyData[$currencyCode]['_digits'];
        } else {
            $baseData[$currencyCode]['fraction_digits'] = $currencyData['DEFAULT']['_digits'];
        }
    }
    ksort($baseData);

    return $baseData;
}

/**
 * Generates the localizations.
 */
function generate_localizations(array $baseData)
{
    global $numbersDirectory;

    $locales = discover_locales();
    // Make sure 'en' is processed first so that it can be used as a fallback.
    $index = array_search('en', $locales);
    unset($locales[$index]);
    array_unshift($locales, 'en');

    $localizations = [];
    $untranslatedCounts = [];
    foreach ($locales as $locale) {
        $data = json_decode(file_get_contents($numbersDirectory . $locale . '/currencies.json'), true);
        $data = $data['main'][$locale]['numbers']['currencies'];
        foreach ($data as $currencyCode => $currency) {
            if (isset($baseData[$currencyCode])) {
                $currencyName = $currency['displayName'];
                // This currency name is untranslated, use the english version.
                if ($currencyCode == $currencyName) {
                    $currencyName = $localizations['en'][$currencyCode]['name'];
                    // Maintain a count of untranslated currencies per locale.
                    $untranslatedCounts += [$locale => 0];
                    $untranslatedCounts[$locale]++;
                }

                $localizations[$locale][$currencyCode] = [
                    'name' => $currencyName,
                ];
                // Decrease the dataset size by exporting the symbol only if it's
                // different from the currency code.
                if ($currency['symbol'] != $currencyCode) {
                    $localizations[$locale][$currencyCode]['symbol'] = $currency['symbol'];
                }
            }
        }
    }

    // Ignore locales that are more than 80% untranslated.
    foreach ($untranslatedCounts as $locale => $count) {
        $totalCount = count($localizations[$locale]);
        $untranslatedPercentage = $count * (100 / $totalCount);
        if ($untranslatedPercentage >= 80) {
            unset($localizations[$locale]);
        }
    }

    return $localizations;
}

/**
 * Filters out duplicate localizations (same as their parent locale).
 *
 * For example, "fr-FR" will be removed if "fr" has the same data.
 */
function filter_duplicate_localizations(array $localizations)
{
    $duplicates = [];
    foreach ($localizations as $locale => $localizedCurrencies) {
        if ($parentLocale = \CommerceGuys\Intl\Locale::getParent($locale)) {
            $parentCurrencies = isset($localizations[$parentLocale]) ? $localizations[$parentLocale] : [];
            $diff = array_udiff($localizedCurrencies, $parentCurrencies, function ($first, $second) {
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
