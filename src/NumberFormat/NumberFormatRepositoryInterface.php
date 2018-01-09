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
     * @param string $locale The locale (i.e. fr-FR).
     *
     * @return NumberFormat
     */
    public function get($locale);
}
