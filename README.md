intl
=====

[![Build Status](https://travis-ci.org/commerceguys/intl.svg?branch=master)](https://travis-ci.org/commerceguys/intl)

A PHP 7.1+ internationalization library, powered by CLDR data.

Features:
- NumberFormatter and CurrencyFormatter, inspired by [intl](http://php.net/manual/en/class.numberformatter.php).
- Currencies
- Languages

Looking for a list of countries and subdivisions? Check out [commerceguys/addressing](https://github.com/commerceguys/addressing).

Why not use the intl extension?
-------------------------------
The intl extension isn't present by default on PHP installs, requiring
it can hurt software adoption.
Behind the scenes the extension relies on libicu which includes the CLDR dataset,
but depending on the OS/distribution used, could be several major CLDR releases behind.

Since the CLDR dataset is freely available in JSON form, it is possible to
reimplement the intl functionality in pure PHP code while ensuring that the
dataset is always fresh.

Having access to the CLDR dataset also makes it possible to offer additional APIs,
such as listing all currencies.

More backstory can be found in [this blog post](https://drupalcommerce.org/blog/15916/commerce-2x-stories-internationalization).

Formatting numbers
------------------
Allows formatting numbers (decimals, percents, currency amounts) using locale-specific rules.

Two formatters are provided for this purpose: [NumberFormatter](https://github.com/commerceguys/intl/blob/master/src/Formatter/NumberFormatterInterface.php) and [CurrencyFormatter](https://github.com/commerceguys/intl/blob/master/src/Formatter/CurrencyFormatterInterface.php).

```php
use CommerceGuys\Intl\Currency\CurrencyRepository;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use CommerceGuys\Intl\Formatter\NumberFormatter;
use CommerceGuys\Intl\Formatter\CurrencyFormatter;

$numberFormatRepository = new NumberFormatRepository;
// Options can be provided to the constructor or the
// individual methods, the locale defaults to 'en' when missing.
$numberFormatter = new NumberFormatter($numberFormatRepository);
echo $numberFormatter->format('1234.99'); // 1,234.99
echo $numberFormatter->format('0.75', ['style' => 'percent']); // 75%

$currencyRepository = new CurrencyRepository;
$currencyFormatter = new CurrencyFormatter($numberFormatRepository, $currencyRepository);
echo $currencyFormatter->format('2.99', 'USD'); // $2.99
// The accounting style shows negative numbers differently and is used
// primarily for amounts shown on invoices.
echo $currencyFormatter->format('-2.99', 'USD', ['style' => 'accounting']); // (2.99$)

// Arabic, Arabic extended, Bengali, Devanagari digits are supported as expected.
$currencyFormatter = new CurrencyFormatter($numberFormatRepository, $currencyRepository, ['locale' => 'ar']);
echo $currencyFormatter->format('1230.99', 'USD'); // US$ ١٬٢٣٠٫٩٩

// Parse formatted values into numeric values.
echo $currencyFormatter->parse('US$ ١٬٢٣٠٫٩٩', 'USD'); // 1230.99
```

Currencies
----------
```php
use CommerceGuys\Intl\Currency\CurrencyRepository;

// Reads the currency definitions from resources/currency.
$currencyRepository = new CurrencyRepository;

// Get the USD currency using the default locale (en).
$currency = $currencyRepository->get('USD');
echo $currency->getCurrencyCode(); // USD
echo $currency->getNumericCode(); // 840
echo $currency->getFractionDigits(); // 2
echo $currency->getName(); // US Dollar
echo $currency->getSymbol(); // $
echo $currency->getLocale(); // en

// Get the USD currency using the fr-FR locale.
$currency = $currencyRepository->get('USD', 'fr-FR');
echo $currency->getName(); // dollar des États-Unis
echo $currency->getSymbol(); // $US
echo $currency->getLocale(); // fr-FR

// Get all currencies, keyed by currency code.
$allCurrencies = $currencyRepository->getAll();
```

Languages
---------
```php
use CommerceGuys\Intl\Language\LanguageRepository;

// Reads the language definitions from resources/language.
$languageRepository = new LanguageRepository;

// Get the german language using the default locale (en).
$language = $languageRepository->get('de');
echo $language->getLanguageCode(); // de
echo $language->getName(); // German

// Get the german language using the fr-FR locale.
$language = $languageRepository->get('de', 'fr-FR');
echo $language->getName(); // allemand

// Get all languages, keyed by language code.
$allLanguages = $languageRepository->getAll();
```

Related projects
----------------
[Laravel integration](https://github.com/Propaganistas/Laravel-Intl/)
