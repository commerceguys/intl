<?php

/**
 * Generates the yml files stored in resources/currency.
 *
 * The ISO currency list is used as a base, since it doesn't contain
 * deprecated currencies, unlike CLDR (v25 has 139 deprecated entries).
 */

set_time_limit(0);

include '../../vendor/autoload.php';

use Symfony\Component\Yaml\Dumper;

$dumper = new Dumper;

// Downloaded from http://www.currency-iso.org/en/home/tables/table-a1.html
$isoCurrencies = '../c2.xml';
// Downloaded from http://unicode.org/Public/cldr/25/json_full.zip
$cldrCurrencies = '../json_full/main/en-US/currencies.json';
if (!file_exists($isoCurrencies)) {
    die("The $isoCurrencies file was not found");
}
if (!file_exists($cldrCurrencies)) {
    die("The $cldrCurrencies file was not found");
}

// Assemble the base data.
$baseData = array();
$data = simplexml_load_file($isoCurrencies);
foreach ($data->CcyTbl->CcyNtry as $currency) {
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
    if (substr($currency['CtryNm'], 0, 2) == 'ZZ' || in_array($currency['Ccy'], array('XUA', 'XSU', 'XDR'))) {
        // Ignore special currencies.
        continue;
    }

    $currencyCode = $currency['Ccy'];
    $baseData[$currencyCode] = array(
        'code' => $currencyCode,
        'numeric_code' => $currency['CcyNbr'],
    );
    // Store fraction_digits only if it differs from the default (2).
    if ($currency['CcyMnrUnts'] != 2) {
        $baseData[$currencyCode]['fraction_digits'] = $currency['CcyMnrUnts'];
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
$currencies = array();
foreach ($locales as $locale) {
    $data = json_decode(file_get_contents('../json_full/main/' . $locale . '/currencies.json'), TRUE);
    $data = $data['main'][$locale]['numbers']['currencies'];
    foreach ($data as $currencyCode => $currency) {
        if (isset($baseData[$currencyCode])) {
            $currencies[$locale][$currencyCode] = array(
                'name' => $currency['displayName'],
                'symbol' => $currency['symbol'],
            );
        }
    }
}

// Identify localizations that are the same as the ones for the parent locale.
// For example, "fr-FR" if "fr" has the same data.
$duplicates = array();
foreach ($currencies as $locale => $localizedCurrencies) {
    if (strpos($locale, '-') !== FALSE) {
        $localeParts = explode('-', $locale);
        array_pop($localeParts);
        $parentLocale = implode('-', $localeParts);
        $diff = array_udiff($localizedCurrencies, $currencies[$parentLocale], function ($first, $second) {
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
    ksort($localizedCurrencies);
    $yaml = $dumper->dump($localizedCurrencies, 3);
    file_put_contents($locale . '.yml', $yaml);
}
