<?php

namespace CommerceGuys\Intl\Tests;

use CommerceGuys\Intl\Locale;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Locale
 */
class LocaleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::canonicalize
     */
    public function testCanonicalize()
    {
        $locale = Locale::canonicalize('BS_cyrl-ba');
        $this->assertEquals('bs-Cyrl-BA', $locale);

        $locale = Locale::canonicalize(null);
        $this->assertEquals(null, $locale);
    }

    /**
     * @covers ::getCandidates
     */
    public function testCandidates()
    {
        $candidates = Locale::getCandidates('bs-Cyrl-BA');
        $this->assertEquals(['bs-Cyrl-BA', 'bs-Cyrl', 'bs'], $candidates);

        $candidates = Locale::getCandidates('sh');
        $this->assertEquals(['sr-Latn', 'sr'], $candidates);
    }

    /**
     * @covers ::resolveAlias
     */
    public function testResolveAlias()
    {
        $locale = Locale::resolveAlias('zh-CN');
        $this->assertEquals('zh-Hans-CN', $locale);

        $locale = Locale::resolveAlias(null);
        $this->assertEquals(null, $locale);
    }
}
