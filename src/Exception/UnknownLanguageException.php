<?php

namespace CommerceGuys\Intl\Exception;

/**
 * This exception is thrown when an unknown language code is passed to the
 * LanguageRepository.
 */
class UnknownLanguageException extends InvalidArgumentException implements ExceptionInterface
{
}
