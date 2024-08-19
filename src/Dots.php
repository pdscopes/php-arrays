<?php

namespace MadeSimple\Arrays;

/**
 * Class Dots
 *
 * @package MadeSimple\Arrays
 */
class Dots implements \ArrayAccess, Arrayable
{
    /**
     * @var array
     */
    private $array;

    /**
     * DotArr constructor.
     *
     * @param array  $array
     */
    function __construct(array $array = [])
    {
        $this->setArray($array);
    }

    /**
     * Store an array.
     *
     * @param array  $array
     */
    public function setArray(array $array)
    {
        if (Arr::accessible($array)) {
            $this->array = $array;
        }
    }
    
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    #[\Override]
    public function toArray()
    {
        return $this->array;
    }

    /**
     * Store an array as a reference.
     *
     * @param array  $array
     */
    public function setReference(&$array)
    {
        if (Arr::isAssoc($array)) {
            $this->array = &$array;
        }
    }


    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return ArrDots::has($this->array, $offset);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return ArrDots::get($this->array, $offset);
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        ArrDots::set($this->array, $offset, $value);
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        ArrDots::remove($this->array, $offset);
    }
}