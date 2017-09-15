<?php

namespace MadeSimple\Arrays\Test\Unit;

use MadeSimple\Arrays\Arr;
use MadeSimple\Arrays\Dots;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    public function testAccessible()
    {
        $this->assertTrue(Arr::accessible([]), 'Array object not accessible');
        $this->assertTrue(Arr::accessible(new Dots()), 'ArrayAccess object not accessible');

        $this->assertFalse(Arr::accessible("Foobar"), 'String is accessible');
        $this->assertFalse(Arr::accessible(123), 'Integer is accessible');
    }

    public function testIsAssoc()
    {
        $this->assertTrue(Arr::isAssoc(['one' => 'value']), 'Associative array not associative');
        $this->assertTrue(Arr::isAssoc([1 => 'value']), 'Associative array not associative');

        $this->assertFalse(Arr::isAssoc(['one']), 'Array is associative');
    }

    public function testDivide()
    {
        $array = [
            'one' => 'value 1',
            'two' => 'value 2',
        ];
        $keys   = ['one', 'two'];
        $values = ['value 1', 'value 2'];

        list($dividedKeys, $dividedValues) = Arr::divide($array);
        $this->assertEquals($keys, $dividedKeys);
        $this->assertEquals($values, $dividedValues);
    }

    public function testFlatten()
    {
        $multiDimensionalArray = [
            'name' => 'Foo Bar',
            'address' => [
                'street'   => '123 Fake St',
                'postCode' => 'AB12 3CD',
            ],
            'age' => 21
        ];
        $flattenedArray = [
            'Foo Bar',
            '123 Fake St',
            'AB12 3CD',
            21,
        ];

        $this->assertEquals($flattenedArray, Arr::flatten($multiDimensionalArray));
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
                'alpha' => 'value a',
                'beta' => 'value b',
            ]
        ];

        $this->assertEquals($partialArray, Arr::only($completeArray, ['two', 'deep']));
    }

    public function testExcept()
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
                'alpha' => 'value a',
                'beta' => 'value b',
            ]
        ];

        $this->assertEquals($partialArray, Arr::except($completeArray, ['one', 'three']));
    }

    public function testFilter()
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
            'deep' => [
                'alpha' => 'value a',
                'beta' => 'value b',
            ]
        ];

        $this->assertEquals($partialArray, Arr::filter($completeArray, function ($item, $key) {
            return in_array($key, ['two', 'deep']) && is_array($item);
        }));
    }

    public function testExists()
    {
        $array = [
            'one' => 'value 1',
            'two' => 'value 2',
            'three' => 'value 3',
            'deep' => [
                'alpha' => 'value a',
                'beta' => 'value b',
            ]
        ];

        $this->assertTrue(Arr::exists($array, 'one'));
        $this->assertTrue(Arr::exists($array, 'two'));
        $this->assertTrue(Arr::exists($array, 'three'));
        $this->assertTrue(Arr::exists($array, 'deep'));

        $this->assertFalse(Arr::exists($array, 'four'));
    }

    public function testFindKey()
    {
        $array = [
            'one' => 'value 1',
            'two' => 'value 2',
            'three' => 'value 3',
            'deep' => [
                'alpha' => 'value a',
                'beta' => 'value b',
            ]
        ];

        $this->assertEquals('one', Arr::findKey($array, 'value 1'));
        $this->assertEquals('two', Arr::findKey($array, 'value 2'));
        $this->assertEquals('three', Arr::findKey($array, 'value 3'));

        $this->assertFalse(Arr::findKey($array, 'four'));
    }

    public function testFindKeyCallable()
    {
        $array = [
            'one' => 'value 1',
            'two' => 'value 2',
            'three' => 'value 3',
            'deep' => [
                'alpha' => 'value a',
                'beta' => 'value b',
            ]
        ];

        $this->assertEquals('one', Arr::findKey($array, function ($item) { return $item === 'value 1';}));
        $this->assertEquals('two', Arr::findKey($array, function ($item) { return $item === 'value 2';}));
        $this->assertEquals('three', Arr::findKey($array, function ($item) { return $item === 'value 3';}));

        $this->assertFalse(Arr::findKey($array, function ($item) { return $item === 'blah';}));
    }

    public function testFind()
    {
        $array = [
            'one' => 'value 1',
            'two' => 'value 2',
            'three' => 'value 3',
            'deep' => [
                'alpha' => 'value a',
                'beta' => 'value b',
            ]
        ];

        $this->assertEquals('value 1', Arr::find($array, 'value 1'));
        $this->assertEquals('value 2', Arr::find($array, 'value 2'));
        $this->assertEquals('value 3', Arr::find($array, 'value 3'));

        $this->assertNull(Arr::find($array, 'four'));
    }

    public function testFindCallable()
    {
        $array = [
            'one' => 'value 1',
            'two' => 'value 2',
            'three' => 'value 3',
            'deep' => [
                'alpha' => 'value a',
                'beta' => 'value b',
            ]
        ];

        $this->assertEquals('value 1', Arr::find($array, function ($item, $key) { return $key === 'one'; }));
        $this->assertEquals('value 2', Arr::find($array, function ($item, $key) { return $key === 'two'; }));
        $this->assertEquals('value 3', Arr::find($array, function ($item, $key) { return $key === 'three'; }));

        $this->assertNull(Arr::find($array, function ($item, $key) { return $key === 'four'; }));
    }
}