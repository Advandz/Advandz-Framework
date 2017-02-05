<?php
/**
 * Provides a keyed hash using HMAC and the Hash of a specific data.
 *
 * @package Advandz
 * @subpackage Advandz.components.hashing
 * @copyright Copyright (c) 2012-2017 CyanDark, Inc. All Rights Reserved.
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 * @author The Advandz Team <team@advandz.com>
 */

namespace Advandz\Component;

class Hashing
{
    /**
     * @var string The quequed data to be hashed
     */
    private $data = '';

    /**
     * Calculate a keyed hash using HMAC.
     *
     * @param string $algorithm Hashing algorithm.
     * @param mixed $data Data or file to be hashed.
     * @param string $key Shared secret key.
     * @param bool $raw Outputs the hash as raw binary data.
     * @param bool $file True, to generate a keyed hash value using the HMAC method and the contents of a given file.
     * @return string Return a keyed hash using HMAC.
     */
    public function hmacHash($algorithm, $data, $key, $raw = false, $file = false)
    {
        if ($file) {
            return hash_hmac_file($algorithm, $data, $key, $raw);
        } else {
            return hash_hmac($algorithm, $data, $key, $raw);
        }
    }

    /**
     * Calculate the Hash of a specific data.
     *
     * @param string $algorithm Hashing algorithm.
     * @param mixed $data Data or file to be hashed.
     * @param bool $raw Outputs the hash as raw binary data.
     * @param bool $file True, to generate a keyed hash value using the contents of a given file.
     * @return string Return the Hash of a specific data.
     */
    public function hash($algorithm, $data, $raw = false, $file = false)
    {
        if ($file) {
            return hash_file($algorithm, $data, $raw);
        } else {
            return hash($algorithm, $data, $raw);
        }
    }

    /**
     * Returns a list of supported hashing algorithms.
     *
     * @return array Return an array containing a list of registered hashing algorithms
     */
    public function listHashAlgorithms()
    {
        return hash_algos();
    }

    /**
     * Initialize an incremental hashing context.
     *
     * @param string $algorithm Hashing algorithm.
     */
    public function startHash($algorithm)
    {
        $this->data = hash_init($algorithm);
    }

    /**
     * Pump data into an active hashing context.
     *
     * @param mixed $data Data or file to be hashed.
     * @param bool $file True, to generate a keyed hash value using the contents of a given file.
     */
    public function addDataToHash($data, $file = false)
    {
        if ($file) {
            hash_update_file($this->data, $data);
        } else {
            hash_update($this->data, $data);
        }
    }

    /**
     * Finalize an incremental hash and return resulting digest.
     *
     * @param string $algorithm Hashing algorithm.
     */
    public function finishHash()
    {
        $hash       = hash_final($this->data);
        $this->data = null;

        return $hash;
    }
}
