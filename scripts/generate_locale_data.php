<?php

/**
 * Generates the $parents array for the Locale class.
 */

$ignoredLocales = [
    // Esperanto, Interlingua, Volapuk are made up languages.
    'eo', 'ia', 'vo',
    // Church Slavic, Manx, Prussian are historical languages.
    'cu', 'gv', 'prg',
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
