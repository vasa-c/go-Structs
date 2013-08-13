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
        $this->array = $array ?: array();
    }

    /**
     * @override \go\Structs\MagicAccess\MagicFullAccess
     *
     * @param string $key
     * @return mixed
     */
    protected function magicGet($key)
    {
        return isset($this->array[$key]) ? $this->array[$key] : null;
    }

    /**
     * @override \go\Structs\MagicAccess\MagicFullAccess
     *
     * @param string $key
     * @return boolean
     */
    protected function magicIsset($key)
    {
        return \array_key_exists($key, $this->array);
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
        $this->array[$key] = $value;
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
        unset($this->array[$key]);
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
    protected $array;
}
