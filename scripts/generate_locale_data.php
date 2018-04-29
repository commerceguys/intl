<?php

/**
 * Generates the $parents array for the Locale class.
 */

$parentLocales = __DIR__ . '/assets/cldr-core/supplemental/parentLocales.json';
$parentLocales = json_decode(file_get_contents($parentLocales), true);
$parentLocales = $parentLocales['supplemental']['parentLocales']['parentLocale'];
$parentLocales = var_export($parentLocales, true) . ';';

$export = "<?php\n\n";
$export .= '$parents = ' . str_replace(['array (', ')'], ['[', ']'], $parentLocales);
$export .= "\n";
file_put_contents(__DIR__ . '/locale_data.php', $export);
