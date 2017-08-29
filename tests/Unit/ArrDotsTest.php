<?php

namespace MadeSimple\Arrays\Test\Unit;

use MadeSimple\Arrays\ArrDots;
use PHPUnit\Framework\TestCase;

class ArrDotsTest extends TestCase
{
    public function testImplode()
    {
        $multiDimensionalArray = [
            'name' => 'Foo Bar',
            'address' => [
                'street'   => '123 Fake St',
                'postCode' => 'AB12 3CD',
            ],
            'age' => 21
        ];
        $implodedArray = [
            'name' => 'Foo Bar',
            'address.street' => '123 Fake St',
            'address.postCode' => 'AB12 3CD',
            'age' => 21,
        ];

        $this->assertEquals($implodedArray, ArrDots::implode($multiDimensionalArray));
    }

    public function testExplode()
    {
        $implodedArray = [
            'name' => 'Foo Bar',
            'address.street' => '123 Fake St',
            'address.postCode' => 'AB12 3CD',
            'age' => 21,
        ];
        $multiDimensionalArray = [
            'name' => 'Foo Bar',
            'address' => [
                'street'   => '123 Fake St',
                'postCode' => 'AB12 3CD',
            ],
            'age' => 21
        ];

        $this->assertEquals($multiDimensionalArray, ArrDots::explode($implodedArray));
    }

    public function testRemove()
    {
        $completeArray = [
            'name' => 'Foo Bar',
            'address' => [
                'street'   => '123 Fake St',
                'postCode' => 'AB12 3CD',
            ],
            'age' => 21
        ];
        $partialArray = [
            'address' => [
                'street' => '123 Fake St'
            ],
            'age' => 21
        ];

        ArrDots::remove($completeArray, ['name', 'address.postCode']);
        $this->assertEquals($partialArray, $completeArray);
    }

    public function testGet()
    {
        $array = [
            'name' => 'Foo Bar',
            'address' => [
                'street'   => '123 Fake St',
                'postCode' => 'AB12 3CD',
            ],
            'age' => 21
        ];

        $this->assertEquals('Foo Bar', ArrDots::get($array, 'name'));
        $this->assertEquals('123 Fake St', ArrDots::get($array, 'address.street'));
        $this->assertEquals('AB12 3CD', ArrDots::get($array, 'address.postCode'));
        $this->assertEquals(21, ArrDots::get($array, 'age'));

        $this->assertEquals('Not There', ArrDots::get($array, 'foobar', 'Not There'));
    }

    public function testHas()
    {
        $array = [
            'name' => 'Foo Bar',
            'address' => [
                'street'   => '123 Fake St',
                'postCode' => 'AB12 3CD',
            ],
            'age' => 21,
            'deep' => [
                ['magic' => 'foo bar'],
                ['magic' => 'bar foo'],
            ]
        ];

        $this->assertTrue(ArrDots::has($array, 'name'));
        $this->assertTrue(ArrDots::has($array, 'address.street'));
        $this->assertTrue(ArrDots::has($array, 'address.postCode'));
        $this->assertTrue(ArrDots::has($array, 'age'));
        $this->assertTrue(ArrDots::has($array, 'deep.*.magic', '*'));

        $this->assertFalse(ArrDots::has($array, 'foobar'));
        $this->assertFalse(ArrDots::has($array, 'deep.*.blah', '*'));
    }

    public function testPull()
    {
        $originalArray = [
            'one' => '1',
            'two' => '2',
            'deep' => [
                'alpha' => 'a',
                'beta'  => 'b'
            ],
        ];
        $alteredArray = [
            'one' => '1',
            'two' => '2',
            'deep' => [
                'beta'  => 'b'
            ],
        ];

        $this->assertEquals('a', ArrDots::pull($originalArray, 'deep.alpha'));
        $this->assertEquals('g', ArrDots::pull($originalArray, 'deep.gamma', 'g'));
        $this->assertEquals($alteredArray, $originalArray);
    }

    public function testSet()
    {
        $originalArray = [
            'one' => '1',
            'deep' => [
                'alpha' => 'a',
            ]
        ];
        $setArray = [
            'one' => 'one',
            'two' => 2,
            'deep' => [
                'alpha' => 'alpha',
                'beta' => 'b',
            ]
        ];

        ArrDots::set($originalArray, 'one', 'one');
        ArrDots::set($originalArray, 'two', 2);
        ArrDots::set($originalArray, 'deep.alpha', 'alpha');
        ArrDots::set($originalArray, 'deep.beta', 'b');

        $this->assertEquals($setArray, $originalArray);
    }

    public function testOnly()
    {
        $completeArray = [
            'one' => 'value 1',
            'two' => 'value 2',
            'three' => 'value 3',
            'deep' => [
                'alpha' => 'value a',
                'beta' => 'value b',
            ]
        ];
        $partialArray = [
            'two' => 'value 2',
            'deep' => [
                'beta' => 'value b',
            ]
        ];

        $this->assertEquals($partialArray, ArrDots::only($completeArray, ['two', 'deep.beta']));
    }
}