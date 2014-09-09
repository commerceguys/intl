<?php

namespace CommerceGuys\Intl\Tests;

use CommerceGuys\Intl\Tests\DummyManager;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \CommerceGuys\Intl\LocaleResolverTrait
 */
class LocaleResolverTest extends \PHPUnit_Framework_TestCase
{
    protected $manager;

    public function setUp()
    {
        // Simulate the presence of various definitions.
        $root = vfsStream::setup('resources');
        vfsStream::newFile('dummy/bs-Cyrl.json')->at($root)->setContent('');
        vfsStream::newFile('dummy/bs.json')->at($root)->setContent('');
        vfsStream::newFile('dummy/en.json')->at($root)->setContent('');

        $this->manager = new DummyManager;
    }

    /**
     * @covers ::resolveLocale
     * @covers ::getLocaleVariants
     */
    public function testLocaleFallback()
    {
        $locale = $this->manager->runResolveLocale('bs-Cyrl-BA');
        $this->assertEquals($locale, 'bs-Cyrl');
        $locale = $this->manager->runResolveLocale('bs-Latn-BA');
        $this->assertEquals($locale, 'bs');
        $locale = $this->manager->runResolveLocale('de', 'en-US');
        $this->assertEquals($locale, 'en');
    }

    /**
     * @covers ::resolveLocale
     * @covers ::getLocaleVariants
     * @expectedException \CommerceGuys\Intl\UnknownLocaleException
     */
    public function testInvalidLocale()
    {
        $locale = $this->manager->runResolveLocale('de');
    }
}
