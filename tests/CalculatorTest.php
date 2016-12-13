<?php

namespace CommerceGuys\Intl\Tests;

use CommerceGuys\Intl\Calculator;

/**
 * @coversDefaultClass \CommerceGuys\Intl\Calculator
 */
class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::add
     * @covers ::subtract
     * @covers ::multiply
     * @covers ::divide
     * @covers ::compare
     * @covers ::trim
     */
    public function testArithmetic()
    {
        $this->assertEquals('11', Calculator::add('5', '6'));
        $this->assertEquals('-9', Calculator::subtract('11', '20'));
        $this->assertEquals('132', Calculator::multiply('11', '12'));
        $this->assertEquals('12.012', Calculator::divide('132.132', '11'));

        $this->assertEquals('3', Calculator::trim('3.00'));
        $this->assertEquals('3.03', Calculator::trim('3.030'));
    }

    /**
     * @covers ::compare
     */
    public function testComparison()
    {
        $this->assertEquals('0', Calculator::compare('1', '1'));
        $this->assertEquals('1', Calculator::compare('2', '1'));
        $this->assertEquals('-1', Calculator::compare('1', '2'));
    }

    /**
    * @covers ::ceil
    * @covers ::floor
    * @covers ::round
    */
    public function testRounding()
    {
        $this->assertEquals('5', Calculator::ceil('4.4'));
        $this->assertEquals('-4', Calculator::ceil('-4.4'));

        $this->assertEquals('4', Calculator::floor('4.8'));
        $this->assertEquals('-5', Calculator::floor('-4.8'));

        $rounding_data = [
            ['1.95583', 2, PHP_ROUND_HALF_UP, '1.96'],
            ['9.4', 0, PHP_ROUND_HALF_UP, '9'],
            ['9.6', 0, PHP_ROUND_HALF_UP, '10'],

            ['9.5', 0, PHP_ROUND_HALF_UP, '10'],
            ['9.5', 0, PHP_ROUND_HALF_DOWN, '9'],
            ['9.5', 0, PHP_ROUND_HALF_EVEN, '10'],
            ['9.5', 0, PHP_ROUND_HALF_ODD, '9'],

            ['8.5', 0, PHP_ROUND_HALF_UP, '9'],
            ['8.5', 0, PHP_ROUND_HALF_DOWN, '8'],
            ['8.5', 0, PHP_ROUND_HALF_EVEN, '8'],
            ['8.5', 0, PHP_ROUND_HALF_ODD, '9'],

            ['1.55', 1, PHP_ROUND_HALF_UP, '1.6'],
            ['1.54', 1, PHP_ROUND_HALF_UP, '1.5'],
            ['-1.55', 1, PHP_ROUND_HALF_UP, '-1.6'],
            ['-1.54', 1, PHP_ROUND_HALF_UP, '-1.5'],

            ['1.55', 1, PHP_ROUND_HALF_DOWN, '1.5'],
            ['1.54', 1, PHP_ROUND_HALF_DOWN, '1.5'],
            ['-1.55', 1, PHP_ROUND_HALF_DOWN, '-1.5'],
            ['-1.54', 1, PHP_ROUND_HALF_DOWN, '-1.5'],

            ['1.55', 1, PHP_ROUND_HALF_EVEN, '1.6'],
            ['1.54', 1, PHP_ROUND_HALF_EVEN, '1.5'],
            ['-1.55', 1, PHP_ROUND_HALF_EVEN, '-1.6'],
            ['-1.54', 1, PHP_ROUND_HALF_EVEN, '-1.5'],

            ['1.55', 1, PHP_ROUND_HALF_ODD, '1.5'],
            ['1.54', 1, PHP_ROUND_HALF_ODD, '1.5'],
            ['-1.55', 1, PHP_ROUND_HALF_ODD, '-1.5'],
            ['-1.54', 1, PHP_ROUND_HALF_ODD, '-1.5'],
        ];
        foreach ($rounding_data as $item) {
            $this->assertEquals($item[3], Calculator::round($item[0], $item[1], $item[2]));
        }
    }
}
