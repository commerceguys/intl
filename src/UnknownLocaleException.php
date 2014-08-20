<?php

namespace CommerceGuys\Intl;

/**
 * This exception is thrown when an unknown locale is passed to a manager class.
 */
class UnknownLocaleException extends InvalidArgumentException implements Exception
{
}
