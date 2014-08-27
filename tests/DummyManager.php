<?php

namespace CommerceGuys\Intl\Tests;

use CommerceGuys\Intl\LocaleResolverTrait;

/**
 * Dummy manager used for testing the LocaleResolverTrait.
 */
class DummyManager
{
    use LocaleResolverTrait;

    public function __construct()
    {
        $this->definitionPath = 'vfs://resources/dummy/';
    }

    public function runResolveLocale($locale, $fallbackLocale = null)
    {
        return $this->resolveLocale($locale, $fallbackLocale);
    }
}
