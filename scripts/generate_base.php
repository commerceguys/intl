<?php

require __DIR__ . '/../vendor/autoload.php';

// Downloaded from http://www.currency-iso.org/en/home/tables/table-a1.html
$isoCurrencies = __DIR__ . '/assets/c2.xml';
// Downloaded from https://github.com/unicode-org/cldr-json.git
$currencyData = __DIR__ . '/assets/cldr/cldr-json/cldr-core/supplemental/currencyData.json';
$localeDirectory = __DIR__ . '/assets/cldr/cldr-json/cldr-localenames-full/main/';
$numbersDirectory = __DIR__ . '/assets/cldr/cldr-json/cldr-numbers-full/main/';

// Preflight checks.
if (!file_exists($currencyData)) {
    die("The $currencyData file was not found");
}
if (!file_exists($isoCurrencies)) {
    die("The $isoCurrencies file was not found");
}
if (!is_dir($localeDirectory)) {
    die("The $localeDirectory directory was not found");
}
if (!is_dir($numbersDirectory)) {
    die("The $numbersDirectory directory was not found");
}
if (!function_exists('collator_create')) {
    // Reimplementing intl's collator would be a huge undertaking, so we
    // use it instead to presort the generated locale specific data.
    die('The intl extension was not found.');
}

// Locales listed without a "-" match all variants.
// Locales listed with a "-" match only those exact ones.
$ignoredLocales = [
    // English is our fallback, we don't need another.
    'und',
    // Esperanto, Interlingua, Volapuk are made up languages.
    'eo', 'ia', 'vo',
    // Belarus (Classical orthography), Church Slavic, Manx, Prussian,
    // Sanskrit are historical languages.
    'be-tarask', 'cu', 'gv', 'prg', 'sa',
    // Valencian differs from its parent only by a single character (è/é).
    'ca-ES-valencia',
    // Africa secondary languages.
    'agq', 'ak', 'am', 'asa', 'bas', 'bem', 'bez', 'bm', 'cgg', 'dav',
    'dje', 'dua', 'dyo', 'ebu', 'ee', 'ewo', 'ff', 'ff-Latn', 'guz',
    'ha', 'ig', 'jgo', 'jmc', 'kab', 'kam', 'kea', 'kde', 'ki', 'kkj',
    'kln', 'khq', 'ksb', 'ksf', 'lag', 'luo', 'luy', 'lu', 'lg', 'ln',
    'mas', 'mer', 'mua', 'mgo', 'mgh', 'mfe', 'naq', 'nd', 'nmg', 'nnh',
    'nus', 'nyn', 'om', 'pcm', 'rof', 'rwk', 'saq', 'seh', 'ses', 'sbp',
    'sg', 'shi', 'sn', 'teo', 'ti', 'tzm', 'twq', 'vai', 'vai-Latn', 'vun',
    'wo', 'xog', 'xh', 'zgh', 'yav', 'yo', 'zu',
    // Europe secondary languages.
    'br', 'dsb', 'fo', 'fur', 'fy', 'hsb', 'ksh', 'kw', 'nds', 'or', 'rm',
    'sc', 'se', 'smn', 'wae',
    // Other infrequently used locales.
    'ceb', 'ccp', 'chr', 'ckb', 'haw', 'ii', 'jv', 'kgp', 'kl', 'kn', 'lkt',
    'lrc', 'mi', 'mzn', 'os', 'qu', 'row', 'sah', 'su', 'tt', 'ug', 'yi',
    'yrl',
];

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
 * Creates a list of available locales.
 */
function discover_locales()
{
    global $localeDirectory, $ignoredLocales;

    // Gather available locales.
    $locales = [];
    foreach (scandir($localeDirectory) as $entry) {
        if (substr($entry, 0, 1) != '.') {
            $entryParts = explode('-', $entry);
            if (!in_array($entry, $ignoredLocales) && !in_array($entryParts[0], $ignoredLocales)) {
                $locales[] = $entry;
            }
        }
    }

    return $locales;
}
