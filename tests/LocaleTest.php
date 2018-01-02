<?php

namespace CommerceGuys\Intl\Tests;

use CommerceGuys\Intl\Exception\UnknownLocaleException;
use CommerceGuys\Intl\Locale;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Locale
 */
class LocaleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::resolve
     */
    public function testResolve()
    {
        $availableLocales = ['bs-Cyrl', 'bs', 'en'];
        $locale = Locale::resolve($availableLocales,'bs-Cyrl-BA');
        $this->assertEquals('bs-Cyrl', $locale);
        $locale = Locale::resolve($availableLocales,'bs-Latn-BA');
        $this->assertEquals('bs', $locale);
        $locale = Locale::resolve($availableLocales,'de', 'en');
        $this->assertEquals('en', $locale);
    }

    /**
     * @covers ::resolve
     */
    public function testResolveWithoutResult()
    {
        $this->setExpectedException(UnknownLocaleException::class);
        $availableLocales = ['bs', 'en'];
        $locale = Locale::resolve($availableLocales,'de');
    }

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
     * @covers ::replaceAlias
     */
    public function testReplaceAlias()
    {
        $locale = Locale::replaceAlias('zh-CN');
        $this->assertEquals('zh-Hans-CN', $locale);

        $locale = Locale::replaceAlias(null);
        $this->assertEquals(null, $locale);
    }
}
