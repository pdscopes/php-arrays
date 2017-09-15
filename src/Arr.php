<?php

namespace MadeSimple\Arrays;

use ArrayAccess;

/**
 * Class Arr
 *
 * @package MadeSimple\Arrays
 */
class Arr
{
    /**
     * Determines if $value is an accessible like an array.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Determines if $array is associative.
     *
     * @param array $array
     *
     * @return bool
     */
    public static function isAssoc(array $array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param array $array
     *
     * @return array [array, array]
     */
    public static function divide($array)
    {
        return [array_keys($array), array_values($array)];
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array
     * @param int $depth
     *
     * @return array
     */
    public static function flatten($array, $depth = INF)
    {
        return array_reduce($array, function ($result, $item) use ($depth) {
            if (!is_array($item)) {
                return array_merge($result, [$item]);
            } else if($depth === 1) {
                return array_merge($result, array_values($item));
            } else {
                return array_merge($result, static::flatten($item, $depth - 1));
            }
        }, []);
    }

    /**
     * Get a subset of the items from $array that only contains $keys.
     *
     * @param array            $array
     * @param int|string|array $keys
     *
     * @return array
     */
    public static function only($array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }

    /**
     * Get a subset of the items from $array that contains all keys except $keys.
     *
     * @param array            $array
     * @param int|string|array $keys
     *
     * @return array
     */
    public static function except($array, $keys)
    {
        return array_diff_key($array, array_flip((array) $keys));
    }

    /**
     * Get a subset of items from $array that pass $callback test.
     *
     * @param ArrayAccess|array $array
     * @param callable          $callback
     *
     * @return array
     */
    public static function filter($array, callable $callback)
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param ArrayAccess|array $array
     * @param string|int        $key
     *
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Search the $haystack and return the first corresponding key if successful.
     * If $needle is a callable then return the first key where the callable
     * returns true.
     *
     * @param array           $haystack
     * @param mixed|callable  $needle
     * @param bool            $strict
     * @return false|int|string
     */
    public static function findKey(array $haystack, $needle, $strict = false)
    {
        if (is_callable($needle)) {
            foreach ($haystack as $key => $item) {
                $result = $needle($item, $key);
                if ((!$strict && $result == true) || ($strict && $result === true)) {
                    return $key;
                }
            }
            return false;
        }
        else {
            return array_search($needle, $haystack, $strict);
        }
    }

    /**
     * Search the $haystack and return the first corresponding element if successful.
     * If $needle is a callable then return the first element where the callable
     * returns true.
     *
     * @param array           $haystack
     * @param mixed|callable  $needle
     * @param bool            $strict
     * @return false|int|string
     */
    public static function find(array $haystack, $needle, $strict = false)
    {
        if (is_callable($needle)) {
            foreach ($haystack as $key => $item) {
                $result = $needle($item, $key);
                if ((!$strict && $result == true) || ($strict && $result === true)) {
                    return $item;
                }
            }
            return null;
        }
        else {
            $key = array_search($needle, $haystack, $strict);
            return $key !== false ? $haystack[$key] : null;
        }
    }
}