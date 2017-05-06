<?php
/**
 * Supplies methods useful in verifying and formatting input data. Provides a
 * number of methods to verify whether the input data is formatted correctly.
 * Supports validating both scalar and array data.
 *
 * @package Advandz
 * @subpackage Advandz.components.input
 * @copyright Copyright (c) 2012-2017 CyanDark, Inc. All Rights Reserved.
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 * @author The Advandz Team <team@advandz.com>
 */

namespace Advandz\Component;

class Input
{
    /**
     * @var array All errors violated in the Input::validates() method
     */
    private $errors = [];

    /**
     * @var array All rules set in Input::setRules()
     */
    private $rules = [];

    /**
     * Checks if the given string is a valid email address.
     *
     * @param  string $str          The string to test
     * @param  bool   $check_record True to check DNS/MX record
     * @return bool   True if the email is valid, false otherwise
     */
    public static function isEmail($str, $check_record = true)
    {
        // Verify that the address is formatted correctly
        if (isset($str) && preg_match("/^[a-z0-9!#$%\*\/?\|^\{\}`~&'\+=_.-]+@[a-z0-9.-]+\.[a-z0-9]{2,10}$/Di", $str, $check)) {
            // Verify that the domain is valid
            if ($check_record) {
                // Append "." to the host name to prevent DNS server from creating the record
                $host = substr(strstr($check[0], '@'), 1) . '.';

                if (function_exists('getmxrr') && !getmxrr($host, $mxhosts)) {
                    // This will catch DNSs that are not MX
                    if (function_exists('checkdnsrr') && !checkdnsrr($host, 'ANY')) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Checks if the given string is empty or otherwise not set.
     *
     * @param  string $str The string to test
     * @return bool   True if the string is empty or not set, false otherwise
     */
    public static function isEmpty($str)
    {
        if (!isset($str) || strlen($str) == 0) {
            return true;
        }

        return false;
    }

    /**
     * Tests whether the given string meets the requirements to be considered a password.
     *
     * @param  string $str          The string to test
     * @param  int    $min_length   The minimum length of the string
     * @param  string $type         Types include "any", "any_no_space", "alpha_num", "alpha", "num", and "custom"
     * @param  string $custom_regex Used when $type is set to "custom". Does not use $min_length, any length requirement
     *                              must be included in the regex
     * @return bool   True if it meets the given requirements, false otherwise
     */
    public static function isPassword($str, $min_length = 6, $type = 'any', $custom_regex = null)
    {
        $regex = null;

        switch ($type) {
            default:
            case 'any':
                $regex = '/.{' . $min_length . ',}/i';
                break;
            case 'any_no_space':
                $regex = "/^[\S]{" . $min_length . ',}$/Di';
                break;
            case 'alpha_num':
                $regex = '/^[a-z0-9]{' . $min_length . ',}$/Di';
                break;
            case 'alpha':
                $regex = '/^[a-z]{' . $min_length . ',}$/Di';
                break;
            case 'num':
                $regex = '/^[0-9]{' . $min_length . ',}$/Di';
                break;
            case 'custom':
                $regex = $custom_regex;
                break;
        }

        return preg_match($regex, $str) == true;
    }

    /**
     * Tests whether the given string is considered a valid date suitable to strtotime().
     *
     * @param  string $str The string to test
     * @param  mixed  $min The minimum acceptable date (string) or unix time stamp (int)
     * @param  mixed  $min The maximum acceptable date (string) or unix time stamp (int)
     * @return bool   True if $str is a valid date, false otherwise
     */
    public static function isDate($str, $min = null, $max = null)
    {
        if (isset($str)) {
            // Convert to UNIX time
            $time = $str;
            if (!is_numeric($str)) {
                $time = strtotime($str);
            }

            // Ensure valid time
            if ($time === false || $time == -1) {
                return false;
            }

            // Check range
            if ($min !== null && (!is_numeric($min) ? $min = strtotime($min) : true) && $time < $min) {
                return false;
            }
            if ($max !== null && (!is_numeric($max) ? $max = strtotime($max) : true) && $time > $max) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Tests wether the given string satisfies the given regular expression.
     *
     * @param  string $str   The string to test
     * @param  string $regex The regular expression to satisfy
     * @return bool   True when the string passes the regex, false otherwise
     */
    public static function matches($str, $regex)
    {
        return (bool) preg_match($regex, $str);
    }

    /**
     * Tests how the given values compare.
     *
     * @param  mixed     $a  The value to compare
     * @param  string    $op The comparison operator: >, <, >=, <=, ==, ===, !=, !==
     * @param  mixed     $b  The value to compare against
     * @return bool      True if $a validates $op against $b, false otherwise
     * @throws Exception Thrown when an unrecognized operator, $op, is given
     */
    public static function compares($a, $op, $b)
    {
        switch ($op) {
            case '>':
                return $a > $b;
            case '<':
                return $a < $b;
            case '>=':
                return $a >= $b;
            case '<=':
                return $a <= $b;
            case '==':
                return $a == $b;
            case '===':
                return $a === $b;
            case '!=':
                return $a != $b;
            case '!==':
                return $a !== $b;
            default:
                throw new \Exception('Unrecognized operator: ' . $op);
        }
    }

    /**
     * Tests that $val is between $min and $max.
     *
     * @param  mixed $val       The value to compare
     * @param  mixed $min       The lower value to compare against
     * @param  mixed $max       The higher value to compare against
     * @param  bool  $inclusive Set to false if $val must be strictly between $min and $max
     * @return bool  True if $val is between $min and $max, false otherwise
     */
    public static function between($val, $min, $max, $inclusive = true)
    {
        if ($inclusive) {
            return $val >= $min && $val <= $max;
        }

        return $val > $min && $val < $max;
    }

    /**
     * Test whether $str is at least $length bytes.
     *
     * @param  string $str    The string to check
     * @param  int    $length The number of bytes required in $str
     * @return bool   True if $str is at least $length bytes
     */
    public static function minLength($str, $length)
    {
        return strlen($str) >= $length;
    }

    /**
     * Test whether $str is no more than $length bytes.
     *
     * @param  string $str    The string to check
     * @param  int    $length The number of bytes allowed in $str
     * @return bool   True if $str is no more than $length bytes
     */
    public static function maxLength($str, $length)
    {
        return strlen($str) <= $length;
    }

    /**
     * Test whether $str is between $min_length and $max_length.
     *
     * @param  string $str        The string to check
     * @param  int    $min_length The number of bytes required in $str
     * @param  int    $max_length The number of bytes allowed in $str
     * @return bool   True if $str is between $min_length and $max_length
     */
    public static function betweenLength($str, $min_length, $max_length)
    {
        return self::minLength($str, $min_length) && self::maxLength($str, $max_length);
    }

    /**
     * Set rules, overriding any existing rules set and empting any existing errors.
     *
     * @param array $rules A multi-deminsional array, where the 1st dimension is the rule.
     */
    public function setRules($rules)
    {
        $this->rules  = $rules;
        $this->errors = [];
    }

    /**
     * Validates all set rules using the given data, sets any error messages to Input::$errors.
     *
     * @param  array $data An array of data such as POST
     * @return bool  true if all rules pass, false if any rule is broken
     * @see Input::errors()
     */
    public function validate(&$data)
    {
        foreach ($this->rules as $rule) {
            // Build function arguments
            if (!empty($rule['options']) || isset($rule['options'])) {
                $arguments = array_merge([$data[$rule['field']]], $rule['options']);
            } elseif (isset($data[$rule['field']])) {
                $arguments = [$data[$rule['field']]];
            } else {
                $arguments = [$rule['field']];
            }

            // Validate rules
            $errors     = [];
            $validation = call_user_func_array([$this, $rule['rule']], $arguments);

            // Negate result
            if ((bool) $rule['negate']) {
                $validation = !$validation;
            }

            // Validate result
            if ($validation == false) {
                $errors[] = $rule['error'];
            }

            // Set errors
            if (!empty($errors)) {
                $this->setErrors($errors);

                return false;
            }

            return true;
        }
    }

    /**
     * Sets the given errors into the object, overriding existing errors (if any).
     *
     * @param array $errors An array of errors as returned by Input::errors()
     * @see Input::errors()
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Return all errors.
     *
     * @return mixed An array of error messages indexed as their field name, boolean false if no errors set
     */
    public function errors()
    {
        if (empty($this->errors)) {
            return false;
        }

        return $this->errors;
    }
}
