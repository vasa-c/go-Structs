<?php
/**
 * Registry
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs;

use go\Structs\Exceptions\ReadOnlyFull;
use go\Structs\Exceptions\ReadOnlyProp;

class Registry extends MagicAccess\MagicArrayLazy implements \Countable, \IteratorAggregate
{
    /**
     * Constructor
     *
     * @param array $vars [optional]
     *        initial variables
     * @param array $lazy [optional]
     *        lazy-vars (key => creator)
     * @param boolean $readonly [optional]
     *        read-only flag
     */
    public function __construct(array $vars = null, array $lazy = null, $readonly = false)
    {
        $this->magicReadOnly = $readonly;
        parent::__construct($vars ?: array(), $lazy ?: array());
    }

    /**
     * Set read-only flag
     */
    public function toReadOnly()
    {
        $this->magicReadOnly = true;
    }

    /**
     * Is read-only?
     *
     * @return boolean
     */
    public function isReadOnly()
    {
        return $this->magicReadOnly;
    }

    /**
     * Set variable value
     *
     * @param string $name
     *        variable name
     * @param mixed $value
     *        variable value
     * @param boolean $const [optional]
     *        constant flag
     * @throws \go\Structs\Exceptions\ReadOnlyFull
     *         registry is read-only
     * @throws \go\Structs\Exceptions\ReadOnlyProp
     *         variable is constant
     */
    public function setVar($name, $value, $const = false)
    {
        $this->magicSet($name, $value);
        if ($const) {
            $this->constants[$name] = true;
        }
    }

    /**
     * Set creator for lazy-var
     *
     * @param string $name
     *        variable name
     * @param callable $creator
     *        function for create value
     * @param boolean $const [optional]
     *        read-only flag
     * @throws \go\Structs\Exceptions\ReadOnlyFull
     *         registry is read-only
     * @throws \go\Structs\Exceptions\ReadOnlyProp
     *         variable is constant
     */
    public function setLazyVar($name, $creator, $const = false)
    {
        if ($this->magicReadOnly) {
            throw new ReadOnlyFull($this->magicContainerName, $name);
        }
        if (isset($this->constants[$const])) {
            throw new ReadOnlyProp($this->magicContainerName, $name);
        }
        if (\array_key_exists($name, $this->magicArray)) {
            return;
        }
        $this->magicCreators[$name] = $creator;
        if ($const) {
            $this->constants[$const] = true;
        }
    }

    /**
     * Is variable constant?
     *
     * @param string $name
     * @return boolean
     */
    public function isConstant($name)
    {
        return isset($this->constants[$name]);
    }

    /**
     * Mark variable as constant
     *
     * @param string $name
     * @throws \go\Structs\Exceptions\ReadOnlyFull
     */
    public function markAsConstant($name)
    {
        if ($this->magicReadOnly) {
            throw new ReadOnlyFull($this->magicContainerName, $name);
        }
        if ((\array_key_exists($name, $this->magicArray)) || (isset($this->magicCreators[$name]))) {
            $this->constants[$name] = true;
        }
    }

    /**
     * Get variable value
     *
     * @param string $name
     * @param mixed $default [optional]
     * @return mixed
     */
    public function getVar($name, $default = null)
    {
        return $this->magicGet($name, $default);
    }

    /**
     * Get variable value if it is loaded
     *
     * @param string $name
     * @param mixed $default [optional]
     * @return mixed
     */
    public function getLoadedVar($name, $default = null)
    {
        if (!\array_key_exists($name, $this->magicArray)) {
            return $default;
        }
        return $this->magicArray[$name];
    }

    /**
     * Get all vars
     *
     * @param boolean $load [optional]
     * @return array
     */
    public function getAllVars($load = true)
    {
        if ($load) {
            foreach ($this->magicCreators as $key => $creator) {
                if (!\array_key_exists($key, $this->magicArray) && $creator) {
                    $this->magicArray[$key] = \call_user_func($creator, $key);
                }
            }
            $this->magicCreators = array();
        }
        return $this->magicArray;
    }

    /**
     * @override \Countable
     *
     * @return int
     */
    public function count()
    {
        $ca = \count($this->magicArray);
        $cl = \count($this->magicCreators);
        if ($cl) {
            $inter = \array_intersect_key($this->magicArray, $this->magicCreators);
            $ca += $cl - \count($inter);
        }
        return $ca;
    }

    /**
     * @override \IteratorAggregate
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getAllVars(true));
    }

    /**
     * @override \go\Structs\MagicAccess\MagicArrayLazy
     *
     * @param string $key
     * @param callable $creator
     */
    public function setItemCreator($key, $creator)
    {
        $this->setLazyVar($key, $creator, false);
    }

    /**
     * @override \go\Structs\MagicAccess\MagicArrayLazy
     *
     * @param string $key
     * @param mixed $value
     */
    protected function magicSet($key, $value)
    {
        if (isset($this->constants[$key])) {
            throw new ReadOnlyProp($this->magicContainerName, $key);
        }
        parent::magicSet($key, $value);
    }

    /**
     * @override \go\Structs\MagicAccess\MagicArrayLazy
     *
     * @param string $key
     */
    protected function magicUnset($key)
    {
        if (isset($this->constants[$key])) {
            throw new ReadOnlyProp($this->magicContainerName, $key);
        }
        parent::magicUnset($key);
    }

    /**
     * List of constants
     *
     * @var array
     */
    protected $constants = array();

    /**
     * @var string
     */
    protected $magicContainerName = 'Registry';
}
