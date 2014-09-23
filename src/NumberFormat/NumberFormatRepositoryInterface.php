<?php

namespace CommerceGuys\Intl\NumberFormat;

/**
 * Number format repository interface.
 */
interface NumberFormatRepositoryInterface
{
    /**
     * Returns a number format instance for the provided locale.
     *
     * @param string $locale The locale (i.e. fr_FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return \CommerceGuys\Intl\NumberFormat\NumberFormatInterface
     */
    public function get($locale, $fallbackLocale = null);
}
