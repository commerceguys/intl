<?php

namespace CommerceGuys\Intl\NumberFormat;

/**
 * Number format repository interface.
 */
interface NumberFormatRepositoryInterface
{
    /**
     * Gets a number format for the provided locale.
     *
     * @param string $locale         The locale (i.e. fr-FR).
     * @param string $fallbackLocale A fallback locale (i.e "en").
     *
     * @return NumberFormat
     */
    public function get($locale, $fallbackLocale = null);
}
