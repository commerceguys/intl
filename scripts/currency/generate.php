<?php

/**
 * Generates the json files stored in resources/currency.
 *
 * The ISO currency list is used as a base, since it doesn't contain
 * deprecated currencies, unlike CLDR (v25 has 139 deprecated entries).
 */

set_time_limit(0);
require '../../vendor/autoload.php';

// Downloaded from http://www.currency-iso.org/en/home/tables/table-a1.html
$isoCurrencies = '../assets/c2.xml';
// Downloaded from https://github.com/unicode-cldr/cldr-numbers-full.git
$numbersDirectory = '../assets/cldr-numbers-full/main/';
$cldrCurrencies = $numbersDirectory . 'en/currencies.json';
// Downloaded from https://github.com/unicode-cldr/cldr-core.git
$currencyData = '../assets/cldr-core/supplemental/currencyData.json';
// Downloaded from https://github.com/unicode-cldr/cldr-localenames-full.git
$localeDirectory = '../assets/cldr-localenames-full/main/';
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

// Assemble the base data.
$baseData = [];
$currencyData = json_decode(file_get_contents($currencyData), true);
$currencyData = $currencyData['supplemental']['currencyData']['fractions'];
$isoData = simplexml_load_file($isoCurrencies);
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

// Make sure 'en' is processed first so that it can be used as a fallback.
$index = array_search('en', $locales);
unset($locales[$index]);
array_unshift($locales, 'en');

// Create the localizations.
$currencies = [];
$untranslatedCounts = [];
foreach ($locales as $locale) {
    $data = json_decode(file_get_contents($numbersDirectory . $locale . '/currencies.json'), true);
    $data = $data['main'][$locale]['numbers']['currencies'];
    foreach ($data as $currencyCode => $currency) {
        if (isset($baseData[$currencyCode])) {
            $currencyName = $currency['displayName'];
            // This currency name is untranslated, use the english version.
            if ($currencyCode == $currencyName) {
                $currencyName = $currencies['en'][$currencyCode]['name'];
                // Maintain a count of untranslated currencies per locale.
                $untranslatedCounts += [$locale => 0];
                $untranslatedCounts[$locale]++;
            }

            $currencies[$locale][$currencyCode] = [
                'name' => $currencyName,
            ];
            // Decrease the dataset size by exporting the symbol only if it's
            // different from the currency code.
            if ($currency['symbol'] != $currencyCode) {
                $currencies[$locale][$currencyCode]['symbol'] = $currency['symbol'];
            }
        }
    }
}

// Ignore locales that are more than 80% untranslated.
foreach ($untranslatedCounts as $locale => $count) {
    $totalCount = count($currencies[$locale]);
    $untranslatedPercentage = $count * (100 / $totalCount);
    if ($untranslatedPercentage >= 80) {
        unset($currencies[$locale]);
    }
}

// Identify localizations that are the same as the ones for the parent locale.
// For example, "fr-FR" if "fr" has the same data.
$duplicates = [];
foreach ($currencies as $locale => $localizedCurrencies) {
    if ($parentLocale = \CommerceGuys\Intl\Locale::getParent($locale)) {
        $parentCurrencies = isset($currencies[$parentLocale]) ? $currencies[$parentLocale] : [];
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
// Remove the duplicates.
foreach ($duplicates as $locale) {
    unset($currencies[$locale]);
}

// Write out the localizations.
foreach ($currencies as $locale => $localizedCurrencies) {
    $collator = collator_create($locale);
    uasort($localizedCurrencies, function ($a, $b) use ($collator) {
        return collator_compare($collator, $a['name'], $b['name']);
    });
    file_put_json($locale . '.json', $localizedCurrencies);
}

// Print the base definitions for CurrencyRepository.
echo "Base data: \n";
echo export_base_data($baseData);
echo "\n\n";

// Print the locales for CurrencyRepository.
$availableLocales = array_keys($currencies);
sort($availableLocales);
echo count($availableLocales) . " available locales: \n";
echo export_locales($availableLocales);

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
    $export = '[' . "\n";
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
    // Join the values with commas.
    $data = implode(', ', $data);
    // Prepare the output array, with indentation.
    $export = '[' . "\n";
    $export .= '    ' . $data . "\n";
    $export .= "];";

    return $export;
}
