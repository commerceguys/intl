<?php

namespace CommerceGuys\Intl\Language;

use CommerceGuys\Intl\InvalidArgumentException;
use CommerceGuys\Intl\Exception;

/**
 * This exception is thrown when an unknown language code is passed to the
 * LanguageManager.
 */
class UnknownLanguageException extends InvalidArgumentException implements Exception
{
}
