<?php

/**
 * Generates the $parents array for the Locale class.
 */

$parentLocales = '../assets/cldr-core/supplemental/parentLocales.json';
$parentLocales = json_decode(file_get_contents($parentLocales), true);
$parentLocales = $parentLocales['supplemental']['parentLocales']['parentLocale'];
$export = var_export($parentLocales, true);
$export = str_replace(['array (', ')'], ['[', ']'], $export);
print $export;
