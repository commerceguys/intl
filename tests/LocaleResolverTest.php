<?php

namespace CommerceGuys\Intl\Tests;

use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \CommerceGuys\Intl\LocaleResolverTrait
 */
class LocaleResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DummyRepository
     */
    protected $repository;

    public function setUp()
    {
        // Simulate the presence of various definitions.
        $root = vfsStream::setup('resources');
        vfsStream::newFile('dummy/bs-Cyrl.json')->at($root)->setContent('');
        vfsStream::newFile('dummy/bs.json')->at($root)->setContent('');
        vfsStream::newFile('dummy/en.json')->at($root)->setContent('');

        $this->repository = new DummyRepository();
    }

    /**
     * @covers ::resolveLocale
     * @covers ::getDefaultLocale
     * @covers ::getLocaleVariants
     *
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::resolveLocaleAlias
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::canonicalizeLocale
     */
    public function testLocaleFallback()
    {
        $locale = $this->repository->runResolveLocale('bs-Cyrl-BA');
        $this->assertEquals('bs-Cyrl', $locale);
        $locale = $this->repository->runResolveLocale('bs-Latn-BA');
        $this->assertEquals('bs', $locale);
        $locale = $this->repository->runResolveLocale('de', 'en');
        $this->assertEquals('en', $locale);
        $locale = $this->repository->runResolveLocale();
        $this->assertEquals('en', $locale);
    }

    /**
     * @covers ::resolveLocaleAlias
     */
    public function testResolveLocaleAlias()
    {
        $locale = $this->repository->runResolveLocaleAlias('zh-CN');
        $this->assertEquals('zh-Hans-CN', $locale);
        $locale = $this->repository->runResolveLocaleAlias(null);
        $this->assertEquals(null, $locale);
    }

    /**
     * @covers ::canonicalizeLocale
     */
    public function testCanonicalizeLocale()
    {
        $locale = $this->repository->runCanonicalizeLocale('BS_cyrl-ba');
        $this->assertEquals('bs-Cyrl-BA', $locale);
        $locale = $this->repository->runCanonicalizeLocale(null);
        $this->assertEquals(null, $locale);
    }

    /**
     * @covers ::resolveLocale
     * @covers ::getLocaleVariants
     * @expectedException \CommerceGuys\Intl\Exception\UnknownLocaleException
     *
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::resolveLocaleAlias
     * @uses \CommerceGuys\Intl\LocaleResolverTrait::canonicalizeLocale
     */
    public function testInvalidLocale()
    {
        $locale = $this->repository->runResolveLocale('de');
    }
}
