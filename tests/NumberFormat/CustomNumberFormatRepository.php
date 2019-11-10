<?php

namespace CommerceGuys\Intl\Tests\NumberFormat;

use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;

/**
 * Provides a custom number format.
 */
class CustomNumberFormatRepository extends NumberFormatRepository
{
    /**
     * {@inheritdoc}
     */
    protected function getDefinitions()
    {
        $return = parent::getDefinitions();
        $return['tst'] = [
          'minus_sign' => "\xc2\xb1",
        ];
        return $return;
    }
}
