<?php

namespace CommerceGuys\Intl\NumberFormat;

class NumberFormat implements NumberFormatEntityInterface
{
    /**
     * The locale (i.e. "en_US").
     *
     * @var string
     */
    protected $locale;

    /**
     * The numbering system.
     *
     * @var string
     */
    protected $numberingSystem = [];

    /**
     * The decimal separator.
     *
     * @var string
     */
    protected $decimalSeparator = [];

    /**
     * The grouping separator.
     *
     * @var string
     */
    protected $groupingSeparator = [];

    /**
     * The plus sign.
     *
     * @var string
     */
    protected $plusSign = [];

    /**
     * The number symbols.
     *
     * @var string
     */
    protected $minusSign = [];

    /**
     * The percent sign.
     *
     * @var string
     */
    protected $percentSign = [];

    /**
     * The number pattern used to format decimal numbers.
     *
     * @var string
     */
    protected $decimalPattern;

    /**
     * The number pattern used to format percentages.
     *
     * @var string
     */
    protected $percentPattern;

    /**
     * The number pattern used to format currency amounts.
     *
     * @var string
     */
    protected $currencyPattern;

    /**
     * The number pattern used to format accounting currency amounts.
     *
     * @var string
     */
    protected $accountingCurrencyPattern;

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

    /**
     * {@inheritdoc}
     */
    public function getNumberingSystem()
    {
        return $this->numberingSystem;
    }

    /**
     * {@inheritdoc}
     */
    public function setNumberingSystem($numberingSystem)
    {
        $this->numberingSystem = $numberingSystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getDecimalSeparator()
    {
        return $this->decimalSeparator;
    }

    /**
     * {@inheritdoc}
     */
    public function setDecimalSeparator($decimalSeparator)
    {
        $this->decimalSeparator = $decimalSeparator;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroupingSeparator()
    {
        return $this->groupingSeparator;
    }

    /**
     * {@inheritdoc}
     */
    public function setGroupingSeparator($groupingSeparator)
    {
        $this->groupingSeparator = $groupingSeparator;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlusSign()
    {
        return $this->plusSign;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlusSign($plusSign)
    {
        $this->plusSign = $plusSign;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinusSign()
    {
        return $this->minusSign;
    }

    /**
     * {@inheritdoc}
     */
    public function setMinusSign($minusSign)
    {
        $this->minusSign = $minusSign;
    }

    /**
     * {@inheritdoc}
     */
    public function getPercentSign()
    {
        return $this->percentSign;
    }

    /**
     * {@inheritdoc}
     */
    public function setPercentSign($percentSign)
    {
        $this->percentSign = $percentSign;
    }

    /**
     * {@inheritdoc}
     */
    public function getDecimalPattern()
    {
        return $this->decimalPattern;
    }

    /**
     * {@inheritdoc}
     */
    public function setDecimalPattern($decimalPattern)
    {
        $this->decimalPattern = $decimalPattern;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPercentPattern()
    {
        return $this->percentPattern;
    }

    /**
     * {@inheritdoc}
     */
    public function setPercentPattern($percentPattern)
    {
        $this->percentPattern = $percentPattern;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyPattern()
    {
        return $this->currencyPattern;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyPattern($currencyPattern)
    {
        $this->currencyPattern = $currencyPattern;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccountingCurrencyPattern()
    {
        return $this->accountingCurrencyPattern;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccountingCurrencyPattern($accountingCurrencyPattern)
    {
        $this->accountingCurrencyPattern = $accountingCurrencyPattern;

        return $this;
    }
}
