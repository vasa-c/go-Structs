<?php
/**
 * Magic access to array
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\MagicAccess;

use go\Structs\Exceptions\ReadOnlyFull;

class MagicArray extends MagicFullAccess
{
    /**
     * Constructor
     *
     * @param array $array [optional]
     */
    public function __construct(array $array = null)
    {
        $this->magicArray = $array ?: array();
    }

    /**
     * @override \go\Structs\MagicAccess\MagicFullAccess
     *
     * @param string $key
     * @param mixed $default [optional]
     * @return mixed
     */
    protected function magicGet($key, $default = null)
    {
        if (\array_key_exists($key, $this->magicArray)) {
            return $this->magicArray[$key];
        }
        return $this->magicGetIfNotExists($key, $default);
    }

    /**
     * For override
     *
     * @param string $key
     * @param mixed $defautl
     * @return mixed
     */
    protected function magicGetIfNotExists($key, $default = null)
    {
        return $default;
    }

    /**
     * @override \go\Structs\MagicAccess\MagicFullAccess
     *
     * @param string $key
     * @return boolean
     */
    protected function magicIsset($key)
    {
        if (\array_key_exists($key, $this->magicArray)) {
            return true;
        }
        return $this->magicIssetIfNotExists($key);
    }

    /**
     * For override
     *
     * @param string $key
     * @return boolean
     */
    protected function magicIssetIfNotExists($key)
    {
        return false;
    }

    /**
     * @override \go\Structs\MagicAccess\MagicFullAccess
     *
     * @param string $key
     * @param mixed $value
     */
    protected function magicSet($key, $value)
    {
        if ($this->magicReadOnly) {
            throw new ReadOnlyFull($this->magicContainerName, $key);
        }
        $this->magicArray[$key] = $value;
    }

    /**
     * @override \go\Structs\MagicAccess\MagicFullAccess
     *
     * @param string $key
     */
    protected function magicUnset($key)
    {
        if ($this->magicReadOnly) {
            throw new ReadOnlyFull($this->magicContainerName, $key);
        }
        unset($this->magicArray[$key]);
    }

    /**
     * Read-only flag (for override)
     *
     * @var boolean
     */
    protected $magicReadOnly = false;

    /**
     * Name for exception message
     *
     * @var string
     */
    protected $magicContainerName = 'MagicArray';

    /**
     * Inner array
     *
     * @var array
     */
    protected $magicArray;
}
