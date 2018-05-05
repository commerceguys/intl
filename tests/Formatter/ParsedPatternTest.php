<?php

namespace CommerceGuys\Intl\Tests\Formatter;

use CommerceGuys\Intl\Formatter\ParsedPattern;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Formatter\ParsedPattern
 */
class ParsedPatternTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getPositivePattern
     * @covers ::getNegativePattern
     * @covers ::isGroupingUsed
     * @covers ::getPrimaryGroupSize
     * @covers ::getSecondaryGroupSize
     */
    public function testBasicPattern()
    {
        $pattern = new ParsedPattern('#,##0.00');

        $this->assertEquals('#,##0.00', $pattern->getPositivePattern());
        $this->assertEquals('-#,##0.00', $pattern->getNegativePattern());
        $this->assertTrue($pattern->isGroupingUsed());
        $this->assertEquals(3, $pattern->getPrimaryGroupSize());
        $this->assertEquals(3, $pattern->getSecondaryGroupSize());
    }

    /**
     * @covers ::__construct
     * @covers ::getPositivePattern
     * @covers ::getNegativePattern
     * @covers ::isGroupingUsed
     * @covers ::getPrimaryGroupSize
     * @covers ::getSecondaryGroupSize
     */
    public function testAdvancedPattern()
    {
        $pattern = new ParsedPattern('#,##,##0.00¤;(#,##,##0.00¤)');

        $this->assertEquals('#,##,##0.00¤', $pattern->getPositivePattern());
        $this->assertEquals('(#,##,##0.00¤)', $pattern->getNegativePattern());
        $this->assertTrue($pattern->isGroupingUsed());
        $this->assertEquals(3, $pattern->getPrimaryGroupSize());
        $this->assertEquals(2, $pattern->getSecondaryGroupSize());
    }
}
