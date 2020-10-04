<?php

namespace CommerceGuys\Intl\Formatter;

use CommerceGuys\Intl\Calculator;
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
     * The default options.
     *
     * @var array
     */
    protected $defaultOptions = [
        'locale' => 'en',
        'use_grouping' => true,
        'minimum_fraction_digits' => 0,
        'maximum_fraction_digits' => 3,
        'rounding_mode' => PHP_ROUND_HALF_UP,
        'style' => 'decimal',
    ];

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
     * @param array                           $defaultOptions         The default options.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct(NumberFormatRepositoryInterface $numberFormatRepository, array $defaultOptions = [])
    {
        if (!extension_loaded('bcmath')) {
            throw new \RuntimeException('The bcmath extension is required by NumberFormatter.');
        }
        $this->validateOptions($defaultOptions);

        $this->numberFormatRepository = $numberFormatRepository;
        $this->defaultOptions = array_replace($this->defaultOptions, $defaultOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function format($number, array $options = [])
    {
        if (!is_numeric($number)) {
            $message = sprintf('The provided value "%s" is not a valid number or numeric string.', $number);
            throw new InvalidArgumentException($message);
        }
        $this->validateOptions($options);
        $options = array_replace($this->defaultOptions, $options);

        $number = (string) $number;
        // Percentages are passed as decimals (e.g. 0.2 for 20%).
        if ($options['style'] == 'percent') {
            $number = Calculator::multiply($number, '100');
        }
        $numberFormat = $this->getNumberFormat($options['locale']);
        $number = $this->formatNumber($number, $numberFormat, $options);

        return $number;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($number, array $options = [])
    {
        $this->validateOptions($options);
        $options = array_replace($this->defaultOptions, $options);
        $numberFormat = $this->getNumberFormat($options['locale']);
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
            'decimal' => $numberFormat->getDecimalPattern(),
            'percent' => $numberFormat->getPercentPattern(),
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
        if (!empty($options['style']) && !in_array($options['style'], ['decimal', 'percent'])) {
            throw new InvalidArgumentException(sprintf('Unrecognized style "%s".', $options['style']));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getLocalizedSymbols(NumberFormat $numberFormat): array
    {
        return [
            '.' => $numberFormat->getDecimalSeparator(),
            ',' => $numberFormat->getGroupingSeparator(),
            '+' => $numberFormat->getPlusSign(),
            '-' => $numberFormat->getMinusSign(),
            '%' => $numberFormat->getPercentSign(),
        ];
    }
}
