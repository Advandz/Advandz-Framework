<?php
/**
 * Provides utility methods to assist in manipulating arrays.
 *
 * @package Advandz
 * @subpackage Advandz.helpers.arrayment
 * @copyright Copyright (c) 2016-2017 Advandz, LLC. All Rights Reserved.
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 * @author The Advandz Team <team@advandz.com>
 */

namespace Advandz\Helper;

class Arrayment
{
    /**
     * Return the first element of an array.
     *
     * @param  array $array The array to fetch the element
     * @return mixed The first element of the given array, false if it's not an array.
     */
    public static function first(array $array)
    {
        if (is_array($array)) {
            $array = array_values($array);

            return $array[0];
        }

        return false;
    }

    /**
     * Return the last element of an array.
     *
     * @param  array $array The array to fetch the element
     * @return mixed The last element of the given array, false if it's not an array.
     */
    public static function last(array $array)
    {
        if (is_array($array)) {
            $array = array_reverse($array);

            return $this->first($array);
        }

        return false;
    }

    /**
     * Checks if the given key exists in the array.
     *
     * @param  string $key   Name key in the array
     * @param  array  $array The array
     * @return mixed  True if exists the key, false otherwise
     */
    public static function keyExists($key, array $array)
    {
        if (is_string($key) && is_array($array)) {
            return array_key_exists($key, $array);
        }

        return false;
    }

    /**
     * Checks if the given value exists in the array.
     *
     * @param  mixed $value The value to find in the array
     * @param  array $array The array
     * @return mixed True if exists the value, false otherwise
     */
    public static function valueExists($value, array $array)
    {
        if (is_array($array)) {
            foreach ($array as $element) {
                if ($element == $value) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Adds (or updates) a given key/value pair to the array.
     *
     * @param  array  $array The array to add the element
     * @param  mixed  $value The value to add to the array
     * @param  string $key   The key to add to the array
     * @return array  The resultant array
     */
    public static function add(&$array, $value, $key = null)
    {
        if (is_array($array)) {
            if (!empty($key) && is_string($key)) {
                $array[$key] = $value;
            } else {
                $array[] = $value;
            }

            return $array;
        }
    }

    /**
     * Removes a element from the array.
     *
     * @param  array  $array The array with the element
     * @param  string $key   The key to delete
     * @return array  The resultant array
     */
    public static function remove(&$array, $key)
    {
        if (is_array($array)) {
            unset($array[$key]);

            return $array;
        }
    }

    /**
     * Fetches a element from the array.
     *
     * @param  array  $array The array with the element
     * @param  string $key   The key to fetch
     * @return mixed  The selected array element
     */
    public static function get(array $array, $key)
    {
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }
    }

    /**
     * Fetches a random element from the array.
     *
     * @param array $array The array
     */
    public static function random(array $array)
    {
        if (is_array($array)) {
            return $array[array_rand($array)];
        }

        return false;
    }

    /**
     * Merge two or more arrays.
     *
     * @param  bool  $recursive True, to merge the arrays in recursive mode
     * @return array The resultant array.
     */
    public static function merge($recursive = false)
    {
        $args = func_get_args();
        $args = array_shift($args);

        if ($recursive) {
            return call_user_func_array('array_merge_recursive', $args);
        } else {
            return call_user_func_array('array_merge', $args);
        }
    }

    /**
     * Combine two arrays.
     *
     * @param  array $array1    The first array
     * @param  array $array2    The second array
     * @param  bool  $recursive True, to merge the arrays in recursive mode
     * @return mixed The resultant array, False if fails.
     */
    public function combine(array $array1, array $array2)
    {
        if (is_array($array1) && is_array($array2)) {
            return array_combine($array1, $array2);
        }

        return false;
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param  array $array       The array to collapse
     * @param  bool  $multi_level Collapse all the levels of the matrix
     * @return mixed The resultant array
     */
    public function collapse($array, $multi_level = false)
    {
        $result = [];

        if (is_array($array)) {
            foreach ($array as $sub_array) {
                if (is_array($sub_array)) {
                    foreach ($sub_array as $value) {
                        if (is_array($value) && $multi_level) {
                            $result = array_merge($result, $this->collapse($value, true));
                        } else {
                            $result[] = $value;
                        }
                    }
                } else {
                    $result[] = $sub_array;
                }
            }

            return $result;
        }

        return $array;
    }

    /**
     * Iteratively reduce the array to a single value using a callback function.
     *
     * @param  array    $array    The array to reduce
     * @param  callable $callback The function to reduce the array to a single value
     * @param  mixed    $initial  If is available, it will be used at the beginning of the process, or as a final result in case the array is empty.
     * @return mixed    Returns the resulting value.
     */
    public function reduce($array, callable $callback, $initial = null)
    {
        return array_reduce($array, $callback, $initial);
    }

    /**
     * Returns two arrays, one containing the keys, and the other containing the values of the original array.
     *
     * @param  array $array The array to split
     * @return mixed The resultant array
     */
    public function split($array)
    {
        return ['keys' => array_keys($array), 'values' => array_values($array)];
    }

    /**
     * Flatten a matrix with dots.
     *
     * @param  array $array   The array to split
     * @param  mixed $prepend The string to prepend to the key
     * @return array The resultant array
     */
    public function dotMatrix(array $array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, $this->dotMatrix($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * Iterates over each value in the array passing them to the callback function.
     * If the callback function returns true, the current value from array is returned
     * into the result array. Array keys are preserved.
     *
     * @param  array    $array    The array to iterate over
     * @param  callable $callback The callback function to use, If no callback is supplied,
     *                            all empty entries will be removed.
     * @return array    Returns the filtered array.
     */
    public function filter(array $array, callable $callback = null)
    {
        if (is_callable($callback)) {
            return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
        } else {
            return array_filter($array);
        }
    }
}
