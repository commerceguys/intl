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
     * The currency display style.
     *
     * @var string
     */
    protected $currencyDisplay = self::CURRENCY_DISPLAY_SYMBOL;

    /**
     * Creates a CurrencyFormatter instance.
     *
     * @param NumberFormatRepositoryInterface $numberFormatRepository The number format repository.
     * @param CurrencyRepositoryInterface     $currencyRepository     The currency repository.
     * @param string                          $defaultLocale          The default locale. Defaults to 'en'.
     *
     * @throws \RuntimeException
     */
    public function __construct(NumberFormatRepositoryInterface $numberFormatRepository, CurrencyRepositoryInterface $currencyRepository, $defaultLocale = 'en')
    {
        if (!extension_loaded('bcmath')) {
            throw new \RuntimeException('The bcmath extension is required by CurrencyFormatter.');
        }

        $this->numberFormatRepository = $numberFormatRepository;
        $this->currencyRepository = $currencyRepository;
        $this->defaultLocale = $defaultLocale;
        $this->style = self::STYLE_STANDARD;
        $this->roundingMode = self::ROUND_HALF_UP;
    }

    /**
     * {@inheritdoc}
     */
    public function format($number, $currencyCode, $locale = null)
    {
        if (!is_numeric($number)) {
            $message = sprintf('The provided value "%s" is not a valid number or numeric string.', $number);
            throw new InvalidArgumentException($message);
        }

        $locale = $locale ?: $this->defaultLocale;
        $numberFormat = $this->getNumberFormat($locale);
        $currency = $this->getCurrency($currencyCode, $locale);

        // Use the currency defaults if the values weren't set by the caller.
        $resetMinimumFractionDigits = $resetMaximumFractionDigits = false;
        if (!isset($this->minimumFractionDigits)) {
            $this->minimumFractionDigits = $currency->getFractionDigits();
            $resetMinimumFractionDigits = true;
        }
        if (!isset($this->maximumFractionDigits)) {
            $this->maximumFractionDigits = $currency->getFractionDigits();
            $resetMaximumFractionDigits = true;
        }

        $number = $this->formatNumber($number, $numberFormat);
        $symbol = '';
        if ($this->currencyDisplay == self::CURRENCY_DISPLAY_SYMBOL) {
            $symbol = $currency->getSymbol();
        } elseif ($this->currencyDisplay == self::CURRENCY_DISPLAY_CODE) {
            $symbol = $currency->getCurrencyCode();
        }
        $number = str_replace('¤', $symbol, $number);

        // Reset the fraction digit settings, so that they don't affect
        // future formatting with different currencies.
        if ($resetMinimumFractionDigits) {
            $this->minimumFractionDigits = null;
        }
        if ($resetMaximumFractionDigits) {
            $this->maximumFractionDigits = null;
        }

        return $number;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($number, $currencyCode, $locale = null)
    {
        $locale = $locale ?: $this->defaultLocale;
        $numberFormat = $this->getNumberFormat($locale);
        $currency = $this->getCurrency($currencyCode, $locale);
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
     * {@inheritdoc}
     */
    public function getCurrencyDisplay()
    {
        return $this->currencyDisplay;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyDisplay($currencyDisplay)
    {
        $this->currencyDisplay = $currencyDisplay;

        return $this;
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
            self::STYLE_STANDARD => $numberFormat->getCurrencyPattern(),
            self::STYLE_ACCOUNTING => $numberFormat->getAccountingCurrencyPattern(),
        ];
    }
}
