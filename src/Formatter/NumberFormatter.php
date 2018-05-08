<?php

namespace CommerceGuys\Intl\Formatter;

use CommerceGuys\Intl\Exception\InvalidArgumentException;
use CommerceGuys\Intl\NumberFormat\NumberFormat;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepositoryInterface;

/**
 * Formats numbers using locale-specific patterns.
 */
class NumberFormatter implements NumberFormatterInterface
{
    use FormatterTrait;

    /**
     * The number format repository.
     *
     * @var NumberFormatRepositoryInterface
     */
    protected $numberFormatRepository;

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
     * Creates a NumberFormatter instance.
     *
     * @param NumberFormatRepositoryInterface $numberFormatRepository The number format repository.
     * @param string                          $defaultLocale          The default locale. Defaults to 'en'.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct(NumberFormatRepositoryInterface $numberFormatRepository, $defaultLocale = 'en')
    {
        if (!extension_loaded('bcmath')) {
            throw new \RuntimeException('The bcmath extension is required by NumberFormatter.');
        }

        $this->numberFormatRepository = $numberFormatRepository;
        $this->defaultLocale = $defaultLocale;
        $this->style = self::STYLE_DECIMAL;
        $this->minimumFractionDigits = 0;
        $this->maximumFractionDigits = 3;
    }

    /**
     * {@inheritdoc}
     */
    public function format($number, $locale = null)
    {
        if (!is_numeric($number)) {
            $message = sprintf('The provided value "%s" is not a valid number or numeric string.', $number);
            throw new InvalidArgumentException($message);
        }
        $locale = $locale ?: $this->defaultLocale;
        $numberFormat = $this->getNumberFormat($locale);
        $number = $this->formatNumber($number, $numberFormat);

        return $number;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($number, $locale = null)
    {
        $locale = $locale ?: $this->defaultLocale;
        $numberFormat = $this->getNumberFormat($locale);
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
     * {@inheritdoc}
     */
    protected function getAvailablePatterns(NumberFormat $numberFormat)
    {
        return [
            self::STYLE_DECIMAL => $numberFormat->getDecimalPattern(),
            self::STYLE_PERCENT => $numberFormat->getPercentPattern(),
        ];
    }
}
