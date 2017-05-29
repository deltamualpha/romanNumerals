<?php

use PHPUnit\Framework\TestCase;

/**
* @author David Ashby <delta.mu.alpha@gmail.com>
*/
class RomanNumeralsTest extends TestCase
{
    public function testSimpleParse()
    {
        $roman = new RomanNumerals();

        $this->assertEquals(1, $roman->parse('I'));
        $this->assertEquals(5, $roman->parse('V'));
        $this->assertEquals(10, $roman->parse('X'));
        $this->assertEquals(50, $roman->parse('L'));
        $this->assertEquals(100, $roman->parse('C'));
        $this->assertEquals(500, $roman->parse('D'));
        $this->assertEquals(1000, $roman->parse('M'));
    }

    public function testComplexParse()
    {
        $roman = new RomanNumerals();

        $this->assertEquals(9, $roman->parse('IX'));
        $this->assertEquals(7, $roman->parse('VII'));
        $this->assertEquals(12, $roman->parse('XII'));
        $this->assertEquals(195, $roman->parse('CXCV'));
        $this->assertEquals(888, $roman->parse('DCCCLXXXVIII'));
        $this->assertEquals(999, $roman->parse('CMXCIX'));
        $this->assertEquals(3888, $roman->parse('MMMDCCCLXXXVIII'));
        $this->assertEquals(4999, $roman->parse('MMMMCMXCIX'));
    }

    public function testCaseInsensitivity()
    {
        $roman = new RomanNumerals();

        $this->assertEquals(9, $roman->parse('Ix'));
        $this->assertEquals(7, $roman->parse('ViI'));
        $this->assertEquals(12, $roman->parse('xii'));
        $this->assertEquals(195, $roman->parse('CxCV'));
        $this->assertEquals(888, $roman->parse('dCCCLXxXVIiI'));
        $this->assertEquals(999, $roman->parse('CMXCix'));
        $this->assertEquals(3888, $roman->parse('mmmdccclxxxviii'));
    }

    public function testInvalidInput()
    {
        $this->expectException(Exception::class);
        new RomanNumerals('ASDFGHJKL');
    }

    public function testInvalidInputArray()
    {
        $this->expectException(Exception::class);
        new RomanNumerals(array('foo'));
    }

    public function testToInt()
    {
        $roman = new RomanNumerals('I');
        $this->assertEquals(1, $roman->toInt());
    }

    public function testToString()
    {
        $roman = new RomanNumerals('I');
        $this->assertEquals('I', (string) $roman);
        $this->assertEquals('I', $roman->toRoman());

        $roman = new RomanNumerals('X');
        $this->assertEquals('X', $roman->toRoman());

        $roman = new RomanNumerals(12);
        $this->assertEquals('XII', $roman->toRoman());

        $roman = new RomanNumerals(195);
        $this->assertEquals('CXCV', $roman->toRoman());

        $roman = new RomanNumerals(3888);
        $this->assertEquals('MMMDCCCLXXXVIII', $roman->toRoman());

        $roman = new RomanNumerals(4999);
        $this->assertEquals('MMMMCMXCIX', $roman->toRoman());

        $roman = new RomanNumerals(5000);
        $this->assertEquals('MMMMM', $roman->toRoman());
    }

    public function testNumberTooSmallToString()
    {
        $roman = new RomanNumerals(-1);
        $this->assertEquals('', (string) $roman);
    }

    public function testNumberTooSmallToRoman()
    {
        $roman = new RomanNumerals(-1);
        $this->expectException(Exception::class);
        $roman->toRoman();
    }

    public function testAddition()
    {
        $roman = new RomanNumerals('X');
        $this->assertEquals(117, $roman->add('VII')->add(100)->toInt());
    }

    public function testSubtraction()
    {
        $roman = new RomanNumerals('XXX');
        $this->assertEquals(14, $roman->subtract('VI')->subtract(10)->toInt());
    }
}