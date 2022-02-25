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

    public function testDivideOnDots()
    {
        $this->assertEquals([[0,1,2,3], [1,2,3,4]], Arr::divide(new Dots([1,2,3,4])));
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

    public function testFlattenOnDots()
    {
        $this->assertEquals([1,2,3], Arr::flatten(new Dots([1,2,3])));
    }

    public function testFlattenOnItemArray()
    {
        $this->assertEquals([1,2,3,4], Arr::flatten([1,2,[3,4]], 1));
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

    public function testCollapseOnDots()
    {
        $original = [
            ['alpha'],
            ['beta'],
            ['gamma'],
        ];
        $collapsed = ['alpha', 'beta', 'gamma'];

        $this->assertEquals($collapsed, Arr::collapse(new Dots($original)));
    }

    public function testCollapse()
    {
        $original = [
            ['alpha'],
            ['beta'],
            ['gamma'],
        ];
        $collapsed = ['alpha', 'beta', 'gamma'];

        $this->assertEquals($collapsed, Arr::collapse($original));
    }

    public function testOnlyOnDots()
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

        $this->assertEquals($partialArray, Arr::only(new Dots($completeArray), ['two', 'deep']));   
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

    public function testExceptOnDots()
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

        $this->assertEquals($partialArray, Arr::except(new Dots($completeArray), ['one', 'three']));

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

    public function testFilterOnDots()
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

        $this->assertEquals($partialArray, Arr::filter(new Dots($completeArray), function ($item, $key) {
            return in_array($key, ['two', 'deep']) && is_array($item);
        }));   
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

    public function testUnique()
    {
        $completeArray = [3,3, 5,5,5,5, 7,7,7,7,7,7, 9,9,9,9,9,9,9,9];
        $filteredArray = [0 => 3, 2 => 5, 6 => 7, 12 => 9];

        $this->assertEquals($filteredArray, Arr::unique($completeArray));

        $completeDots = new Dots($completeArray);
        $this->assertEquals($filteredArray, Arr::unique($completeDots));
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

        $this->assertEquals($partialArray1, Arr::column($completeArray, 'one'));
        $this->assertEquals($partialArray2, Arr::column($completeArray, ['two', 'alpha'], 'id'));
        $this->assertEquals($partialArray3, Arr::column($completeArray, ['three', 0, 'epsilon']));
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

        $this->assertEquals($partialArray1, Arr::pluck($completeArray, 'one'));
        $this->assertEquals($partialArray2, Arr::pluck($completeArray, ['two', 'alpha']));
        $this->assertEquals($partialArray3, Arr::pluck($completeArray, ['three', 0, 'epsilon']));
        $this->assertEquals($partialArray4, Arr::pluck($completeArray, ['four', null, 'gamma']));
    }

    public function testExistsOnDots()
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

        $this->assertTrue(Arr::exists(new Dots($array), 'one'));
        $this->assertTrue(Arr::exists(new Dots($array), 'two'));
        $this->assertTrue(Arr::exists(new Dots($array), 'three'));
        $this->assertTrue(Arr::exists(new Dots($array), 'deep'));

        $this->assertFalse(Arr::exists(new Dots($array), 'four'));
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

    public function testSearchKey()
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

        $this->assertEquals('one', Arr::searchKey($array, 'value 1'));
        $this->assertEquals('two', Arr::searchKey($array, 'value 2'));
        $this->assertEquals('three', Arr::searchKey($array, 'value 3'));

        $this->assertFalse(Arr::searchKey($array, 'four'));
    }

    public function testSearchKeyCallable()
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

        $this->assertEquals('one', Arr::searchKey($array, function ($item) { return $item === 'value 1';}));
        $this->assertEquals('two', Arr::searchKey($array, function ($item) { return $item === 'value 2';}));
        $this->assertEquals('three', Arr::searchKey($array, function ($item) { return $item === 'value 3';}));

        $this->assertFalse(Arr::searchKey($array, function ($item) { return $item === 'blah';}));
    }

    public function testSearch()
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

        $this->assertEquals('value 1', Arr::search($array, 'value 1'));
        $this->assertEquals('value 2', Arr::search($array, 'value 2'));
        $this->assertEquals('value 3', Arr::search($array, 'value 3'));

        $this->assertNull(Arr::search($array, 'four'));
    }

    public function testSearchCallable()
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

        $this->assertEquals('value 1', Arr::search($array, function ($item, $key) { return $key === 'one'; }));
        $this->assertEquals('value 2', Arr::search($array, function ($item, $key) { return $key === 'two'; }));
        $this->assertEquals('value 3', Arr::search($array, function ($item, $key) { return $key === 'three'; }));

        $this->assertNull(Arr::search($array, function ($item, $key) { return $key === 'four'; }));
    }

    public function testLocateOnPropertyNotFound()
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

        $this->assertNull(Arr::locate($shallow, 'invalid_property', '1a'));
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

        $this->assertEquals('alpha', Arr::locate($shallow, 'locator', '1a')['description']);
        $this->assertEquals('beta', Arr::locate($shallow, 'locator', '2b')['description']);
        $this->assertEquals('gamma', Arr::locate($shallow, 'locator', '3c')['description']);

        $this->assertEquals('alpha', Arr::locate($deep, ['sub', 'locator'], '1a')['description']);
        $this->assertEquals('beta', Arr::locate($deep, ['sub', 'locator'], '2b')['description']);
        $this->assertEquals('gamma', Arr::locate($deep, ['sub', 'locator'], '3c')['description']);
    }
}