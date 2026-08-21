---
name: cldr-update
description: "Update commerceguys/intl's currency, language, locale, and number-format data to a new CLDR release. Use when the user says 'update to CLDR vNN', 'update CLDR data', or asks to bump the CLDR version in this repo."
---

# CLDR update (commerceguys/intl)

Regenerates `intl`'s data from a new CLDR release and merges it into `src/`.
CLDR releases twice a year (late March, late October). Confirm a new release
exists at https://cldr.unicode.org/ before starting — the JSON mirror at
https://github.com/unicode-org/cldr-json often lands a few days after the
official release, so check its `cldr-core/package.json` `version` field too.

## 1. Fetch fresh data

```
cd scripts
sh fetch_data.sh   # clones unicode-org/cldr-json (shallow) into assets/, fetches ISO 4217 currency list
```

Confirm the version actually pulled:
```
grep version scripts/assets/cldr/cldr-json/cldr-core/package.json
```

## 2. Generate

```
cd scripts
rm -rf currency language   # stale output from a prior run, if present
php generate_currency_data.php && php generate_language_data.php \
  && php generate_locale_data.php && php generate_number_format_data.php
```

This produces `scripts/currency/`, `scripts/language/` (JSON per locale) and
`scripts/currency_data.php`, `scripts/language_data.php`, `scripts/locale_data.php`,
`scripts/number_formats.php` (PHP snippets to hand-merge into `src/`).

**Known trap:** `generate_number_format_data.php` exports string values into
single-quoted PHP without escaping embedded `'`. CLDR data can and does use `'`
itself as a grouping separator (e.g. `de-CH`, `de-LI`), which produces invalid
PHP. Fix is already applied in this repo (`addslashes($value)` before export)
— if it regresses, re-apply it and regenerate.

## 3. Copy resources

```
cd ..
rm -rf resources/currency resources/language
cp -r scripts/currency resources/currency
cp -r scripts/language resources/language
```

## 4. Merge generated data into src/

Don't hand-edit this — write throwaway PHP merge scripts (`include` the
generated file, `preg_replace` the corresponding array/method body in the
target file with a freshly formatted block), then `php -l` to check syntax.
This avoids transcription errors on large arrays.

- `scripts/currency_data.php` (`$locales`, `$baseData`) →
  `src/Currency/CurrencyRepository.php` (`$availableLocales`, `getBaseDefinitions()`)
- `scripts/language_data.php` (`$locales`) →
  `src/Language/LanguageRepository.php` (`$availableLocales`)
- `scripts/locale_data.php` (`$parents`) →
  `src/Locale.php` (`$parents`) — diff first; this list rarely changes but
  occasionally does (e.g. new region reparenting to `en-150`)
- `scripts/number_formats.php` (`$numberFormats`) →
  `src/NumberFormat/NumberFormatRepository.php` (`getDefinitions()`)

Match existing formatting: 8-space indented, comma-separated, wrapped ~80 cols
for locale arrays; nested associative arrays at 12-space indent for number
formats.

## 5. Clean up scratch output

```
rm -rf scripts/assets scripts/currency scripts/language \
  scripts/currency_data.php scripts/language_data.php \
  scripts/locale_data.php scripts/number_formats.php
```

## 6. Test

```
vendor/bin/phpunit
```

Failures here are often genuine CLDR data changes surfacing in fixtures (e.g.
a locale's grouping/decimal separator changed), not bugs — diff the failure
against the new source-of-truth JSON/PHP before assuming the test is wrong.
Fix the fixture only once you've confirmed the new expected value against the
regenerated data.

## 7. Commit, tag, push

Follow the repo's existing terse convention — check `git tag --sort=-v:refname`
for the next patch version and `git log --oneline` for message style:

```
git add src/ resources/ tests/ scripts/  # only files that actually changed
git commit -m "Update to CLDR vNN.N."
git tag vX.Y.Z
git push origin master && git push origin vX.Y.Z
```

Then edit https://github.com/commerceguys/intl/releases for the new tag:
title `vX.Y.Z`, description summarizing the notable changes (added/removed
locales, base currency data changes, format changes, any bugs fixed along the
way). Ask the user before pushing or editing the GitHub release if not
explicitly authorized in the current conversation.
