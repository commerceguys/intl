<?php

namespace CommerceGuys\Intl\Exception;

/**
 * This exception is thrown when an unknown locale is passed to a repository.
 */
class UnknownLocaleException extends InvalidArgumentException implements ExceptionInterface
{
}
