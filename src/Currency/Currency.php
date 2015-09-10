<?php

namespace CommerceGuys\Intl\Currency;

class Currency implements CurrencyEntityInterface
{
    /**
     * The alphanumeric currency code.
     *
     * @var string
     */
    protected $currencyCode;

    /**
     * The currency name.
     *
     * @var string
     */
    protected $name;

    /**
     * The numeric currency code.
     *
     * @var string
     */
    protected $numericCode;

    /**
     * The currency symbol.
     *
     * @var string
     */
    protected $symbol;

    /**
     * The number of fraction digits.
     *
     * @var int
     */
    protected $fractionDigits;

    /**
     * The currency locale (i.e. "en_US").
     *
     * The currency name and symbol are locale specific.
     *
     * @var string
     */
    protected $locale;

    /**
     * Returns the string representation of the currency.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getCurrencyCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNumericCode()
    {
        return $this->numericCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setNumericCode($numericCode)
    {
        $this->numericCode = $numericCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * {@inheritdoc}
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFractionDigits()
    {
        return $this->fractionDigits;
    }

    /**
     * {@inheritdoc}
     */
    public function setFractionDigits($fractionDigits)
    {
        $this->fractionDigits = $fractionDigits;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
