<?php

namespace MadeSimple\Arrays\Test\Unit;

use MadeSimple\Arrays\Dots;
use PHPUnit\Framework\TestCase;

class DotsTest extends TestCase
{
    public function testToArray()
    {
        $array  = ['one' => 1, 'deep' => ['alpha' => 'a']];
        $dotArr = new Dots($array);

        $this->assertEquals($array, $dotArr->toArray());
    }

    public function testExists()
    {
        $dotArr = new Dots(['one' => 1, 'deep' => ['alpha' => 'a']]);

        $this->assertTrue(isset($dotArr['one']));
        $this->assertTrue(isset($dotArr['deep.alpha']));
        $this->assertFalse(isset($dotArr['two']));
        $this->assertFalse(isset($dotArr['deep.beta']));
    }

    public function testGet()
    {
        $dotArr = new Dots(['one' => 1, 'deep' => ['alpha' => 'a']]);

        $this->assertEquals(1, $dotArr['one']);
        $this->assertEquals('a', $dotArr['deep.alpha']);
    }

    public function testSetReference()
    {
        $refArr = ['one' => 1];
        $dotArr = new Dots(['one' => 1, 'deep' => ['alpha' => 'a']]);
        $dotArr->setReference($refArr);

        $this->assertEquals($refArr, $dotArr->toArray());
    }

    public function testSet()
    {
        $dotArr = new Dots(['one' => 1, 'deep' => ['alpha' => 'a']]);

        $dotArr['one'] = 'ONE';
        $this->assertEquals('ONE', $dotArr['one']);

        $dotArr['deep.beta'] = 'BETA';
        $this->assertEquals('BETA', $dotArr['deep.beta']);

        $dotArr['deep.alpha'] = 'ALPHA';
        $this->assertEquals('ALPHA', $dotArr['deep.alpha']);

    }

    public function testUnset()
    {
        $dotArr = new Dots(['one' => 1, 'deep' => ['alpha' => 'a'], 'two' => 2]);

        $this->assertTrue(isset($dotArr['deep.alpha']));
        unset($dotArr['deep.alpha']);
        $this->assertFalse(isset($dotArr['deep.alpha']));

        $this->assertTrue(isset($dotArr['one']));
        unset($dotArr['one']);
        $this->assertFalse(isset($dotArr['one']));
    }
}