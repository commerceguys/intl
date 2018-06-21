<?php

namespace CommerceGuys\Intl\Formatter;

use CommerceGuys\Intl\Currency\Currency;
use CommerceGuys\Intl\Currency\CurrencyRepositoryInterface;
use CommerceGuys\Intl\Exception\InvalidArgumentException;
use CommerceGuys\Intl\Exception\UnknownCurrencyException;
use CommerceGuys\Intl\NumberFormat\NumberFormat;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepositoryInterface;

/**
 * Formats currency amounts using locale-specific patterns.
 */
class CurrencyFormatter implements CurrencyFormatterInterface
{
    use FormatterTrait;

    /**
     * The number format repository.
     *
     * @var NumberFormatRepositoryInterface
     */
    protected $numberFormatRepository;

    /**
     * The currency repository.
     *
     * @var CurrencyRepositoryInterface
     */
    protected $currencyRepository;

    /**
     * The default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * The loaded number formats.
     *
     * @var NumberFormat[]
     */
    protected $numberFormats = [];

    /**
     * The loaded currencies.
     *
     * @var Currency[]
     */
    protected $currencies = [];

    /**
     * The default options.
     *
     * @var array
     */
    protected $defaultOptions = [
        'locale' => 'en',
        'use_grouping' => true,
        'minimum_fraction_digits' => null,
        'maximum_fraction_digits' => null,
        'rounding_mode' => PHP_ROUND_HALF_UP,
        'style' => 'standard',
        'currency_display' => 'symbol',
    ];

    /**
     * Creates a CurrencyFormatter instance.
     *
     * @param NumberFormatRepositoryInterface $numberFormatRepository The number format repository.
     * @param CurrencyRepositoryInterface     $currencyRepository     The currency repository.
     * @param array                           $defaultOptions         The default options.
     *
     * @throws \RuntimeException
     */
    public function __construct(NumberFormatRepositoryInterface $numberFormatRepository, CurrencyRepositoryInterface $currencyRepository, array $defaultOptions = [])
    {
        if (!extension_loaded('bcmath')) {
            throw new \RuntimeException('The bcmath extension is required by CurrencyFormatter.');
        }
        $this->validateOptions($defaultOptions);

        $this->numberFormatRepository = $numberFormatRepository;
        $this->currencyRepository = $currencyRepository;
        $this->defaultOptions = array_replace($this->defaultOptions, $defaultOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function format($number, $currencyCode, array $options = [])
    {
        if (!is_numeric($number)) {
            $message = sprintf('The provided value "%s" is not a valid number or numeric string.', $number);
            throw new InvalidArgumentException($message);
        }

        $this->validateOptions($options);
        $options = array_replace($this->defaultOptions, $options);
        $numberFormat = $this->getNumberFormat($options['locale']);
        $currency = $this->getCurrency($currencyCode, $options['locale']);
        // Use the currency defaults if the values weren't set by the caller.
        if (!isset($options['minimum_fraction_digits'])) {
            $options['minimum_fraction_digits'] = $currency->getFractionDigits();
        }
        if (!isset($options['maximum_fraction_digits'])) {
            $options['maximum_fraction_digits'] = $currency->getFractionDigits();
        }

        $number = (string) $number;
        $number = $this->formatNumber($number, $numberFormat, $options);
        if ($options['currency_display'] == 'symbol') {
            $number = str_replace('¤', $currency->getSymbol(), $number);
        } elseif ($options['currency_display'] == 'code') {
            $number = str_replace('¤', $currency->getCurrencyCode(), $number);
        } else {
            // No symbol should be displayed. Remove leftover whitespace.
            $number = str_replace('¤', '', $number);
            $number = trim($number, " \xC2\xA0");
        }

        return $number;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($number, $currencyCode, array $options = [])
    {
        $this->validateOptions($options);
        $options = array_replace($this->defaultOptions, $options);
        $numberFormat = $this->getNumberFormat($options['locale']);
        $currency = $this->getCurrency($currencyCode, $options['locale']);
        $replacements = [
            // Strip the currency code or symbol.
            $currency->getCurrencyCode() => '',
            $currency->getSymbol() => '',
        ];
        $number = strtr($number, $replacements);
        $number = $this->parseNumber($number, $numberFormat);

        return $number;
    }

    /**
     * Gets the number format for the provided locale.
     *
     * @param string $locale The locale.
     *
     * @return NumberFormat
     */
    protected function getNumberFormat($locale)
    {
        if (!isset($this->numberFormats[$locale])) {
            $this->numberFormats[$locale] = $this->numberFormatRepository->get($locale);
        }

        return $this->numberFormats[$locale];
    }

    /**
     * Gets the currency for the provided currency code and locale.
     *
     * @param string $currencyCode The currency code.
     * @param string $locale       The locale.
     *
     * @return Currency
     */
    protected function getCurrency($currencyCode, $locale)
    {
        if (!isset($this->currencies[$currencyCode][$locale])) {
            try {
                $currency = $this->currencyRepository->get($currencyCode, $locale);
            } catch (UnknownCurrencyException $e) {
                // The requested currency was not found. Fall back
                // to a dummy object to show just the currency code.
                $currency = new Currency([
                   'currency_code' => $currencyCode,
                   'name' => $currencyCode,
                   'numeric_code' => '000',
                   'locale' => $locale,
                ]);
            }
            $this->currencies[$currencyCode][$locale] = $currency;
        }

        return $this->currencies[$currencyCode][$locale];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAvailablePatterns(NumberFormat $numberFormat)
    {
        return [
            'standard' => $numberFormat->getCurrencyPattern(),
            'accounting' => $numberFormat->getAccountingCurrencyPattern(),
        ];
    }

    /**
     * Validates the provided options.
     *
     * Ensures the absence of unknown keys, correct data types and values.
     *
     * @param array $options The options.
     *
     * @throws \InvalidArgumentException
     */
    protected function validateOptions(array $options)
    {
        foreach ($options as $option => $value) {
            if (!array_key_exists($option, $this->defaultOptions)) {
                throw new InvalidArgumentException(sprintf('Unrecognized option "%s".', $option));
            }
        }
        if (isset($options['use_grouping']) && !is_bool($options['use_grouping'])) {
            throw new InvalidArgumentException('The option "use_grouping" must be a boolean.');
        }
        foreach (['minimum_fraction_digits', 'maximum_fraction_digits'] as $option) {
            if (array_key_exists($option, $options) && !is_numeric($options[$option])) {
                throw new InvalidArgumentException(sprintf('The option "%s" must be numeric.', $option));
            }
        }
        $roundingModes = [
            PHP_ROUND_HALF_UP, PHP_ROUND_HALF_DOWN,
            PHP_ROUND_HALF_EVEN, PHP_ROUND_HALF_ODD, 'none',
        ];
        if (!empty($options['rounding_mode']) && !in_array($options['rounding_mode'], $roundingModes)) {
            throw new InvalidArgumentException(sprintf('Unrecognized rounding mode "%s".', $options['rounding_mode']));
        }
        if (!empty($options['style']) && !in_array($options['style'], ['standard', 'accounting'])) {
            throw new InvalidArgumentException(sprintf('Unrecognized style "%s".', $options['style']));
        }
        if (!empty($options['currency_display']) && !in_array($options['currency_display'], ['code', 'symbol', 'none'])) {
            throw new InvalidArgumentException(sprintf('Unrecognized currency display "%s".', $options['currency_display']));
        }
    }
}
