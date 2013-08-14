<?php
/**
 * Magic array with lazy load
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\MagicAccess;

class MagicArrayLazy extends MagicArray
{
    /**
     * Constructor
     *
     * @param array $array
     *        initial state of inner array
     * @param array $creators [optional]
     *        creators for items (key => callable)
     */
    public function __construct(array $array, array $creators = null)
    {
        $this->magicCreators = $creators ?: array();
        parent::__construct($array);
    }

    /**
     * Set item creator
     *
     * @param string $key
     * @param callable $creator
     *        (NULL for remove)
     */
    public function setItemCreator($key, $creator)
    {
        $this->magicCreators[$key] = $creator;
    }

    /**
     * @override \go\Structs\MagicAccess\MagicArray
     *
     * @param string $key
     * @param mixed $default [optional]
     * @return mixed
     */
    protected function magicGetIfNotExists($key, $default = null)
    {
        if (!isset($this->magicCreators[$key])) {
            return $default;
        }
        $value = \call_user_func($this->magicCreators[$key], $key);
        $this->magicArray[$key] = $value;
        return $value;
    }
    /**
     * @override \go\Structs\MagicAccess\MagicArray
     *
     * @param string $key
     * @return boolean
     */
    protected function magicIssetIfNotExists($key)
    {
        return isset($this->magicCreators[$key]);
    }

    /**
     * @override \go\Structs\MagicAccess\MagicArrayLazy
     *
     * @param string $key
     */
    protected function magicUnset($key)
    {
        unset($this->magicCreators[$key]);
        parent::magicUnset($key);
    }

    /**
     * @var array
     */
    protected $magicCreators;
}
