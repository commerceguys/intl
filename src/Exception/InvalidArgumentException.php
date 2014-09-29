<?php

namespace CommerceGuys\Intl\Exception;

/**
 * This exception is thrown when an invalid argument is passed to a method.
 * For example, a float amount instead of the expected string amount.
 */
class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
}
