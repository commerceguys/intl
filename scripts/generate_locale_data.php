<?php

/**
 * Generates the $parents array for the Locale class.
 */

$ignoredLocales = [
    // Esperanto, Interlingua, Volapuk are made up languages.
    'eo', 'ia', 'vo',
    // Church Slavic, Manx, Prussian are historical languages.
    'cu', 'gv', 'prg',
    // Africa secondary languages.
    'agq', 'ak', 'am', 'asa', 'bas', 'bem', 'bez', 'bm', 'cgg', 'dav',
    'dje', 'dua', 'dyo', 'ebu', 'ee', 'ewo', 'ff', 'ff-Latn', 'guz',
    'ha', 'ig', 'jgo', 'jmc', 'kab', 'kam', 'kea', 'kde', 'ki', 'kkj',
    'kln', 'khq', 'ksb', 'ksf', 'lag', 'luo', 'luy', 'lu', 'lg', 'ln',
    'mas', 'mer', 'mua', 'mgo', 'mgh', 'mfe', 'naq', 'nd', 'nmg', 'nnh',
    'nus', 'nyn', 'om', 'rof', 'rwk', 'saq', 'seh', 'ses', 'sbp', 'sg',
    'shi', 'sn', 'teo', 'ti', 'tzm', 'twq', 'vai', 'vai-Latn', 'vun',
    'wo', 'xog', 'xh', 'zgh', 'yav', 'yo', 'zu',
    // Europe secondary languages.
    'br', 'dsb', 'fo', 'fur', 'fy', 'hsb', 'ksh', 'kw', 'nds', 'or', 'rm',
    'se', 'smn', 'wae',
    // Other infrequently used locales.
    'ceb', 'ccp', 'chr', 'ckb', 'haw', 'ii', 'jv', 'kl', 'kn', 'lkt',
    'lrc', 'mi', 'mzn', 'os', 'qu', 'row', 'sah', 'tt', 'ug', 'yi',
];

$parentLocales = __DIR__ . '/assets/cldr-core/supplemental/parentLocales.json';
$parentLocales = json_decode(file_get_contents($parentLocales), true);
$parentLocales = $parentLocales['supplemental']['parentLocales']['parentLocale'];
foreach ($parentLocales as $locale => $parentLocale) {
    $localeParts = explode('-', $locale);
    if (in_array($localeParts[0], $ignoredLocales)) {
        unset($parentLocales[$locale]);
    }
}

$parentLocales = var_export($parentLocales, true) . ';';
$export = "<?php\n\n";
$export .= '$parents = ' . str_replace(['array (', ')'], ['[', ']'], $parentLocales);
$export .= "\n";
file_put_contents(__DIR__ . '/locale_data.php', $export);
