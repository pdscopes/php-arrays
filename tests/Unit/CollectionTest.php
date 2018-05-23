<?php

namespace MadeSimple\Arrays\Test\Unit;

use MadeSimple\Arrays\Collection;
use MadeSimple\Arrays\Dots;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testConstructorOnCollection()
    {
        $collection = new Collection(new Collection([1,2]));
        $expectedArray = [1,2];

        $this->assertEquals($expectedArray, $collection->toArray());
    }

    public function testConstructorOnTraversable()
    {
        $collection = new Collection(new \ArrayIterator([1, 2, 3]));
        $expectedArray = [1,2,3];

        $this->assertEquals($expectedArray, $collection->toArray());
    }

    public function testConstructorOnArrayable()
    {
        $collection = new Collection(new Dots(['one' => 1]));
        $expectedArray = ['one' => 1];

        $this->assertEquals($expectedArray, $collection->toArray());
    }

    public function testAll()
    {
        $items = [1, 2, 3, 4];
        $collection = new Collection($items);

        $this->assertEquals($items, $collection->all());
    }

    public function testSlice()
    {
        $items = [1, 2, 3, 4];
        $slice = array_slice($items, 1, 2);
        $collection = new Collection($items);

        $this->assertEquals($slice, $collection->slice(1, 2));
    }

    public function testFirst()
    {
        $items = [2,4,6,8];
        $collection = new Collection($items);

        $this->assertEquals(2, $collection->first());
    }

    public function testNth()
    {
        $items = [2,4,6,8];
        $collection = new Collection($items);

        $this->assertEquals(2, $collection->nth(0));
        $this->assertEquals(4, $collection->nth(1));
        $this->assertEquals(6, $collection->nth(2));
        $this->assertEquals(8, $collection->nth(3));
    }

    public function testNthOnInvalidPosition()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Position');

        $items = [2,4,6,8];
        $collection = new Collection($items);
        $collection->nth(-10);
    }

    public function testLast()
    {
        $items = [2,4,6,8];
        $collection = new Collection($items);

        $this->assertEquals(8, $collection->last());
    }

    public function testEach()
    {
        $items = [1, 2, 3, 4];
        $collection = new Collection($items);

        $sum = new \stdClass;
        $sum->value = 0;
        $collection->each(function ($item) use ($sum) { $sum->value += $item; });
        $this->assertEquals(array_sum($items), $sum->value);
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

        $collection = new Collection($multiDimensionalArray);
        $flattened  = $collection->flatten();

        $this->assertEquals($multiDimensionalArray, $collection->all());
        $this->assertEquals($flattenedArray, $flattened->all());
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

        $collection = new Collection($completeArray);
        $only       = $collection->only('two', 'deep');

        $this->assertEquals($completeArray, $collection->all());
        $this->assertEquals($partialArray, $only->all());
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

        $collection = new Collection($completeArray);
        $except     = $collection->except('one', 'three');

        $this->assertEquals($completeArray, $collection->all());
        $this->assertEquals($partialArray, $except->all());
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
        $collection = new Collection($completeArray);
        $filtered   = $collection->filter(function ($item, $key) {
            return in_array($key, ['two', 'deep']) && is_array($item);
        });

        $this->assertEquals($completeArray, $collection->all());
        $this->assertEquals($partialArray, $filtered->all());
    }

    public function testFilterOnNull()
    {
        $assocArray = ['one' => 1, 'two' => 2];
        $collection = new Collection($assocArray);
        $filtered = $collection->filter();

        $this->assertEquals($assocArray, $collection->all());
    }

    public function testUnique()
    {
        $completeArray = [
            'one' => 'value a',
            'two' => 'value b',
            'three' => 'value a',
            'four' => 'value b'
        ];
        $uniqueArray = [
            'one' => 'value a',
            'two' => 'value b',
        ];
        $collection = new Collection($completeArray);
        $filtered   = $collection->unique();

        $this->assertEquals($completeArray, $collection->all());
        $this->assertEquals($uniqueArray, $filtered->all());
    }

    public function testFind()
    {
        $items = [
            ['id' => 1, 'value' => 'alpha'],
            ['id' => 2, 'value' => 'beta'],
            ['id' => 3, 'value' => 'gamma'],
        ];
        $collection = new Collection($items);

        $this->assertEquals(['id' => 2, 'value' => 'beta'], $collection->search(function ($item) {
            return $item['id'] == 2;
        }));
    }

    public function testMap()
    {
        $items    = [1, 2, 3, 4];
        $expected = [2, 4, 6, 8];

        $collection = new Collection($items);
        $doubled    = $collection->map(function ($item) { return $item + $item; });

        $this->assertEquals($items, $collection->all());
        $this->assertEquals($expected, $doubled->all());
    }

    public function testMerge()
    {
        $items1 = [1,2,3,4];
        $items2 = [5,6,7,8];

        $collection = new Collection($items1);
        $merged     = $collection->merge($items2);

        $this->assertEquals($items1, $collection->all());
        $this->assertEquals(array_merge($items1, $items2), $merged->all());
    }

    public function testCombine()
    {
        $keyArray   = ['one', 'two', 'three'];
        $valueArray = ['alpha', 'beta', 'gamma'];

        $keyCollection = new Collection($keyArray);
        $combined    = $keyCollection->combine($valueArray);

        $this->assertEquals($keyArray, $keyCollection->all());
        $this->assertEquals(array_combine($keyArray, $valueArray), $combined->all());
    }

    public function testUnion()
    {
        $items1 = ['one' => 'alpha', 'two' => 'beta'];
        $items2 = ['two' => 'foobar', 'three' => 'gamma'];

        $collection = new Collection($items1);
        $unioned    = $collection->union($items2);

        $this->assertEquals($items1, $collection->all());
        $this->assertEquals($items1 + $items2, $unioned->all());

    }

    public function testSort()
    {
        $array       = [2,1,4,3];
        $sortedArray = [1,2,3,4];

        $collection = new Collection($array);
        $sorted     = $collection->sort();

        $this->assertEquals($array, $collection->all());
        foreach ($sortedArray as $k => $v) {
            $this->assertEquals($v, $sorted->nth($k));
        }
    }

    public function testSortWithCallable()
    {
        $array       = [2,1,4,3];
        $sortedArray = [4,3,2,1];

        $collection = new Collection($array);
        $sorted     = $collection->sort(function ($a, $b) {
            return $b > $a;
        });

        $this->assertEquals($array, $collection->all());
        foreach ($sortedArray as $k => $v) {
            $this->assertEquals($v, $sorted->nth($k));
        }
    }

    public function testFlip()
    {
        $array = ['one' => 'alpha', 'two' => 'beta', 'three' => 'gamma'];

        $collection = new Collection($array);
        $flipped    = $collection->flip();

        $this->assertEquals(array_flip($array), $flipped->all());
    }

    public function testKeys()
    {
        $array = ['one' => 'alpha', 'two' => 'beta', 'three' => 'gamma'];

        $collection = new Collection($array);
        $keys       = $collection->keys();

        $this->assertEquals(array_keys($array), $keys->all());
    }

    public function testValues()
    {
        $array = ['one' => 'alpha', 'two' => 'beta', 'three' => 'gamma'];

        $collection = new Collection($array);
        $values     = $collection->values();

        $this->assertEquals(array_values($array), $values->all());
    }

    public function testGet()
    {
        $items = ['one' => 'alpha', 'two' => 'beta', 'three' => 'gamma'];
        $collection = new Collection($items);

        $this->assertEquals('alpha', $collection->get('one'));
        $this->assertEquals('beta', $collection->get('two'));
        $this->assertEquals('gamma', $collection->get('three'));
        $this->assertEquals(null, $collection->get('four', null));
    }

    public function testHas()
    {
        $items = ['one' => 'alpha', 'two' => 'beta', 'three' => 'gamma'];
        $collection = new Collection($items);

        $this->assertTrue($collection->has('one'));
        $this->assertTrue($collection->has('two'));
        $this->assertTrue($collection->has('three'));
        $this->assertFalse($collection->has('four'));
    }

    public function testIsEmpty()
    {
        $items1 = [];
        $items2 = [1,2,3,4];
        $collection1 = new Collection($items1);
        $collection2 = new Collection($items2);

        $this->assertTrue($collection1->isEmpty());
        $this->assertFalse($collection2->isEmpty());
    }

    public function testImplode()
    {
        $items       = [1.5,2.5,3.5,4.5];
        $collection = new Collection($items);

        $this->assertEquals('1.5,2.5,3.5,4.5', $collection->implode(','));
        $this->assertEquals('1,2,3,4', $collection->implode(',', 'floor'));
        $this->assertEquals('2,3,4,5', $collection->implode(',', 'ceil'));
    }

    public function testToArray()
    {
        $items      = [1,2,3,4];
        $collection = new Collection($items);

        $this->assertEquals($items, $collection->toArray());
    }

    public function testJsonSerialize()
    {
        $items      = [1,2,3,4];
        $collection = new Collection($items);

        $this->assertEquals($items, $collection->jsonSerialize());
    }

    public function testJsonSerializeOnCollection()
    {
        $items      = [new Collection([1,2]),new Dots(['one' => 1]),3,4];
        $collection = new Collection($items);
        $expectedArray = [[1,2],['one' => 1],3,4];

        $this->assertEquals($expectedArray, $collection->jsonSerialize());
    }

    public function testToString()
    {
        $json       = '[1,2,3,4]';
        $items      = [1,2,3,4];
        $collection = new Collection($items);

        $this->assertEquals($json, $collection->__toString());
    }

    public function testArrayAccessOffsetExists()
    {
        $items = [1,2,3,4];
        $collection = new Collection($items);

        foreach ($items as $k => $v) {
            $this->assertTrue(isset($collection[$k]));
        }
    }

    public function testArrayAccessOffsetGet()
    {
        $items = [1,2,3,4];
        $collection = new Collection($items);

        foreach ($items as $k => $v) {
            $this->assertEquals($v, $collection[$k]);
        }
    }

    public function testArrayAccessOffsetSet()
    {
        $items = [1,2,3,4];
        $collection = new Collection($items);

        foreach ($collection as $k => $v) {
            $collection[$k] = 2*$v;
        }

        foreach ($items as $k => $v) {
            $this->assertEquals(2*$v, $collection[$k]);
        }
    }

    public function testArrayAccessOffsetUnset()
    {
        $items = [1,2,3,4];
        $collection = new Collection($items);

        $this->assertTrue(isset($collection[1]));
        unset($collection[1]);
        $this->assertFalse(isset($collection[1]));
        $this->assertEquals(3, $collection->count());
    }

    public function testCount()
    {
        $items1 = [1,2,3,4];
        $items2 = [1,2,3,4,5,6];
        $collection1 = new Collection($items1);
        $collection2 = new Collection($items2);

        $this->assertEquals(4, $collection1->count());
        $this->assertEquals(6, $collection2->count());
    }

    public function testGetIterator()
    {
        $items      = [1,2,3,4];
        $collection = new Collection($items);
        $iterator   = $collection->getIterator();
        $this->assertInstanceOf(\Iterator::class, $iterator);
        $this->assertEquals($items, iterator_to_array($iterator));
    }
}