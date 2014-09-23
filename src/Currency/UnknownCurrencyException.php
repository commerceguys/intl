<?php

namespace CommerceGuys\Intl\Currency;

use CommerceGuys\Intl\InvalidArgumentException;
use CommerceGuys\Intl\Exception;

/**
 * This exception is thrown when an unknown currency code is passed to the
 * CurrencyRepository.
 */
class UnknownCurrencyException extends InvalidArgumentException implements Exception
{
}
