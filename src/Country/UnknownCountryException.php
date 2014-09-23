<?php

namespace CommerceGuys\Intl\Country;

use CommerceGuys\Intl\InvalidArgumentException;
use CommerceGuys\Intl\Exception;

/**
 * This exception is thrown when an unknown country code is passed to the
 * CountryRepository.
 */
class UnknownCountryException extends InvalidArgumentException implements Exception
{
}
