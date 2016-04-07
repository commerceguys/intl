intl
=====

[![Build Status](https://travis-ci.org/commerceguys/intl.svg?branch=master)](https://travis-ci.org/commerceguys/intl)

A PHP 5.4+ internationalization library, powered by CLDR data.

Features:
- NumberFormatter, inspired by [intl](http://php.net/manual/en/class.numberformatter.php).
- Currencies
- Countries
- Languages

Coming soon: date formatting.

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
Formats numbers (decimals, percents, currency amounts) using locale-specific rules.

This ensures that the decimal and grouping separators, the position of the currency
symbol, as well as the actual symbol used match what the user is expecting.

The amounts passed for formatting should already be rounded, because the
formatter doesn't do any rounding of its own.

```php
use CommerceGuys\Intl\Currency\CurrencyRepository;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use CommerceGuys\Intl\Formatter\NumberFormatter;

$currencyRepository = new CurrencyRepository;
$numberFormatRepository = new NumberFormatRepository;

$currency = $currencyRepository->get('USD');
$numberFormat = $numberFormatRepository->get('en');

$decimalFormatter = new NumberFormatter($numberFormat);
echo $decimalFormatter->format('1234.99'); // 123,456.99

$percentFormatter = new NumberFormatter($numberFormat, NumberFormatter::PERCENT);
echo $percentFormatter->format('0.75'); // 75%

$currencyFormatter = new NumberFormatter($numberFormat, NumberFormatter::CURRENCY);
echo $currencyFormatter->formatCurrency('2.99', $currency); // $2.99

// The accounting pattern shows negative numbers differently and is used
// primarily for amounts shown on invoices.
$invoiceCurrencyFormatter = new NumberFormatter($numberFormat, NumberFormatter::CURRENCY_ACCOUNTING);
echo $invoiceCurrencyFormatter->formatCurrency('-2.99', $currency); // (2.99$)

// Arabic, Arabic extended, Bengali, Devanagari digits are supported as expected.
$currency = $currencyRepository->get('USD', 'ar');
$numberFormat = $numberFormatRepository->get('ar');
$currencyFormatter = new NumberFormatter($numberFormat, NumberFormatter::CURRENCY);
echo $currencyFormatter->formatCurrency('1230.99', $currency); // US$ ١٬٢٣٠٫٩٩

// Parse formatted values into numeric values.
echo $currencyFormatter->parseCurrency('US$ ١٬٢٣٠٫٩٩', $currency); // 1230.99
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

$allCurrencies = $currencyRepository->getAll();
```

Countries
---------
```php
use CommerceGuys\Intl\Country\CountryRepository;

// Reads the country definitions from resources/country.
$countryRepository = new CountryRepository;

// Get the US country using the default locale (en).
$country = $countryRepository->get('US');
echo $country->getCountryCode(); // US
echo $country->getName(); // United States
echo $country->getCurrencyCode(); // USD

// Get the US country using the fr-FR locale.
$country = $countryRepository->get('US', 'fr-FR');
echo $country->getName(); // États-Unis

$allCountries = $countryRepository->getAll();
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

$allLanguages = $languageRepository->getAll();
```

Implementing the library
------------------------
The base interfaces don't impose setters.
Extended interfaces (with setters) are provided for (Doctrine, Drupal) entities.

While the library can be used as-is, many applications will want to store parts of the dataset in a database.
This allows for better performance while giving users the ability to modify and expand the data.
Taking currencies as an example, a merchant frequently wants to be able to:

- Define custom currencies.
- Enable/disable existing currencies
- Modify an existing currency (changing the default number of fraction digits, for example).

This would be accomplished by using the CurrencyRepository to get all default currencies and
insert them into the database. The doctrine entity (or any similar data object) would then implement
the CurrencyEntityInterface so that the NumberFormatter can continue to work.

Related projects
----------------
[commerceguys/pricing](http://github.com/commerceguys/pricing) provides a Price object.
