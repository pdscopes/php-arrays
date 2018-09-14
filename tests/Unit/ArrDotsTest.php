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

    public function testColumn()
    {
        $completeArray = [
            [
                'one' => '1st one',
                'two' => ['alpha' => '1st two alpha', 'beta' => '1st two beta', 'id' => '1A'],
                'three' => [['gamma' => '1st gamma', 'epsilon' => '1st epsilon']]
            ],
            [
                'one' => '2nd one',
                'two' => ['alpha' => '2nd two alpha', 'beta' => '2nd two beta', 'id' => '2B'],
                'three' => [['gamma' => '2nd gamma', 'epsilon' => '2nd epsilon']]
            ],
            [
                'one' => '3rd one',
                'two' => ['alpha' => '3rd two alpha', 'beta' => '3rd two beta', 'id' => '3C'],
                'three' => [['gamma' => '3rd gamma', 'epsilon' => '3rd epsilon']]
            ],
        ];
        $partialArray1 = [
            '1st one',
            '2nd one',
            '3rd one',
        ];
        $partialArray2 = [
            '1A' => '1st two alpha',
            '2B' => '2nd two alpha',
            '3C' => '3rd two alpha',
        ];
        $partialArray3 = [
            '1st epsilon',
            '2nd epsilon',
            '3rd epsilon',
        ];

        $this->assertEquals($partialArray1, ArrDots::column($completeArray, 'one'));
        $this->assertEquals($partialArray2, ArrDots::column($completeArray, 'two.alpha', 'id'));
        $this->assertEquals($partialArray3, ArrDots::column($completeArray, 'three.0.epsilon'));
    }

    public function testPluck()
    {
        $completeArray = [
            [
                'one' => '1st one',
                'two' => ['alpha' => '1st two alpha', 'beta' => '1st two beta', 'id' => '1A'],
                'three' => [['gamma' => '1st gamma', 'epsilon' => '1st epsilon']],
                'four' => [['gamma' => '1st gamma 1st'], ['gamma' => '1st gamma 2nd']],
            ],
            [
                'one' => '2nd one',
                'two' => ['alpha' => '2nd two alpha', 'beta' => '2nd two beta', 'id' => '2B'],
                'three' => [['gamma' => '2nd gamma', 'epsilon' => '2nd epsilon']],
                'four' => [['gamma' => '2nd gamma 1st'], ['gamma' => '2nd gamma 2nd']],
            ],
            [
                'one' => '3rd one',
                'two' => ['alpha' => '3rd two alpha', 'beta' => '3rd two beta', 'id' => '3C'],
                'three' => [['gamma' => '3rd gamma', 'epsilon' => '3rd epsilon']],
                'four' => [['gamma' => '3rd gamma 1st'], ['gamma' => '3rd gamma 2nd']],
            ],
        ];
        $partialArray1 = [
            '1st one',
            '2nd one',
            '3rd one',
        ];
        $partialArray2 = [
            '1st two alpha',
            '2nd two alpha',
            '3rd two alpha',
        ];
        $partialArray3 = [
            '1st epsilon',
            '2nd epsilon',
            '3rd epsilon',
        ];
        $partialArray4 = [
            '1st gamma 1st',
            '1st gamma 2nd',
            '2nd gamma 1st',
            '2nd gamma 2nd',
            '3rd gamma 1st',
            '3rd gamma 2nd',
        ];

        $this->assertEquals($partialArray1, ArrDots::pluck($completeArray, 'one'));
        $this->assertEquals($partialArray2, ArrDots::pluck($completeArray, 'two.alpha'));
        $this->assertEquals($partialArray3, ArrDots::pluck($completeArray, 'three.0.epsilon'));
        $this->assertEquals($partialArray4, ArrDots::pluck($completeArray, 'four.*.gamma', '*'));
    }

    public function testRemoveOnNotFoundKey()
    {
        $completeArray = [
            'name' => 'Foo Bar',
            'address' => [
                'street'   => '123 Fake St',
                'postCode' => 'AB12 3CD',
            ],
            'age' => 21
        ];

        $expectedArray = [
            'address' => [
                'street'   => '123 Fake St',
                'postCode' => 'AB12 3CD',
            ],
            'age' => 21,
            'name' => 'Foo Bar',
        ];

        ArrDots::remove($completeArray, ['name.address']);
        $this->assertEquals($expectedArray, $completeArray);
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

    public function testRemoveOnEmptyKeys()
    {
        $emptyArray = [];

        $this->assertNull(ArrDots::remove($emptyArray, []));
    }

    public function testRemoveOnInvalidArray()
    {
        $invalidArray = 100;

        $this->assertNull(ArrDots::remove($invalidArray, ['keys']));
    }

    public function testGetOnInvalidArray()
    {
        $invalidArray = 100;

        $this->assertNull(ArrDots::get($invalidArray, ['keys']));
    }

    public function testGetOnNullKey()
    {
        $array = [];

        $this->assertEquals($array, ArrDots::get($array, null));
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

    public function testCollateOnNotAccessibleArray()
    {
        $data = ['field0' => 'field0-value'];

        $this->assertEquals([], ArrDots::collate($data, 'field0.*', '*'));
    }
 
    public function testCollateOnNonExistedSegment()
    {
        $data = [
            'field0' => ['field0-value'],
            'field1' => 'field1-value',
        ];

        $this->assertEquals([], ArrDots::collate($data, 'field0.*.@', '@'));
    }

    public function testCollate()
    {
        $data = [
            'field0' => 'field0-value',
            'array0' => [1, 2, 3, 4],
            'array1' => [
                ['item0' => 'item0-value'],
                ['item0' => 'item1-value'],
            ],
            'array2' => [
                ['sub-array0' => [1, 2, 3, 4]],
                ['sub-array0' => [1, 2, 3, 4]],
            ],
            'array3' => [
                ['sub-array1' => [['item1' => 'item2-value'], ['item1' => 'item3-value']]],
                ['sub-array1' => [['item1' => 'item4-value']]],
            ],
        ];

        $this->assertEquals([], ArrDots::collate($data, 'field1'));
        $this->assertEquals([], ArrDots::collate($data, 'field0.*'));
        $this->assertEquals([], ArrDots::collate($data, 'array1.item1'));
        $this->assertEquals([
            'field0' => 'field0-value'
        ], ArrDots::collate($data, 'field0'));
        $this->assertEquals([
            'array0' => [1, 2, 3, 4]
        ], ArrDots::collate($data, 'array0'));
        $this->assertEquals([
            'array0.0' => 1,
            'array0.1' => 2,
            'array0.2' => 3,
            'array0.3' => 4,
        ], ArrDots::collate($data, 'array0.*', '*'));
        $this->assertEquals([
            'array2.0.sub-array0' => [1, 2, 3, 4],
            'array2.1.sub-array0' => [1, 2, 3, 4],
        ], ArrDots::collate($data, 'array2.*.sub-array0', '*'));
        $this->assertEquals([
            'array3.0.sub-array1.0.item1' => 'item2-value',
            'array3.0.sub-array1.1.item1' => 'item3-value',
            'array3.1.sub-array1.0.item1' => 'item4-value',
        ], ArrDots::collate($data, 'array3.*.sub-array1.*.item1', '*'));
    }

    public function testHasOnWildcardKey()
    {
        $array = [
            'one' => 'one1',
            'two' => 'two2',
            '*' => [
                'found',
            ],
        ];

        $this->assertTrue(ArrDots::has($array, ['one.*'], '*'));
    } 

    public function testHasOnNullKey()
    {
        $array = ['one' => 1, 'two' => 2];

        $this->assertFalse(ArrDots::has($array, null));
    }

    public function testHasOnEmptyArray()
    {
        $array = [];

        $this->assertFalse(ArrDots::has($array, ['keys']));
    }

    public function testHasOnMismatchKeyArrayToArrayString()
    {
        $array = ['one' => 'string'];
        $this->assertFalse(ArrDots::has($array, 'one.*.one', '*'));

        $array = ['foo' => ['field0' => 'value0']];
        $this->assertFalse(ArrDots::has($array, 'foo.*.field1', '*'));
    }

    public function testHasOnMultipleKeys()
    {
        $array = ['foo' => [['field0' => 'value0', 'field1' => 'value0']]];

        $this->assertTrue(ArrDots::has($array, ['foo.*.field0', 'foo.*.field1'], '*'));
        $this->assertFalse(ArrDots::has($array, ['foo.*.field0', 'foo.*.field2'], '*'));
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

    public function testSetOnNullKey()
    {
        $array = ['one' => 1];

        $this->assertEquals(2, ArrDots::set($array, null, 2));
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

    public function testLocate()
    {
        $shallow = [
            ['locator' => '1a', 'description' => 'alpha'],
            ['locator' => '2b', 'description' => 'beta'],
            ['locator' => '3c', 'description' => 'gamma'],
        ];
        $deep = [
            ['sub' => ['locator' => '1a', 'description' => 'alpha']],
            ['sub' => ['locator' => '2b', 'description' => 'beta']],
            ['sub' => ['locator' => '3c', 'description' => 'gamma']],
        ];

        $this->assertEquals('alpha', ArrDots::locate($shallow, 'locator', '1a')['description']);
        $this->assertEquals('beta', ArrDots::locate($shallow, 'locator', '2b')['description']);
        $this->assertEquals('gamma', ArrDots::locate($shallow, 'locator', '3c')['description']);

        $this->assertEquals('alpha', ArrDots::locate($deep, 'sub.locator', '1a')['description']);
        $this->assertEquals('beta', ArrDots::locate($deep, 'sub.locator', '2b')['description']);
        $this->assertEquals('gamma', ArrDots::locate($deep, 'sub.locator', '3c')['description']);
    }
}