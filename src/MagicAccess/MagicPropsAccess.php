<?php
/**
 * Basic implementation IMagicPropsAccess
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\MagicAccess;

abstract class MagicPropsAccess implements IMagicPropsAccess
{
    /**
     * @override \go\Structs\MagicAccess\IMagicPropsAccess
     *
     * @param string $key
     * @return mixed
     */
    final public function __get($key)
    {
        return $this->magicGet($key);
    }

    /**
     * @override \go\Structs\MagicAccess\IMagicPropsAccess
     *
     * @param string $key
     * @return boolean
     */
    final public function __isset($key)
    {
        return $this->magicIsset($key);
    }

    /**
     * @override \go\Structs\MagicAccess\IMagicPropsAccess
     *
     * @param string $key
     * @param mixed $value
     */
    final public function __set($key, $value)
    {
        $this->magicSet($key, $value);
    }

    /**
     * @override \go\Structs\MagicAccess\IMagicPropsAccess
     *
     * @param string $key
     */
    final public function __unset($key)
    {
        $this->magicUnset($key);
    }

    /**
     * Get inner value by key
     *
     * @param string $key
     * @return mixed
     */
    abstract protected function magicGet($key);

    /**
     * Set inner value
     *
     * @param string $key
     * @param mixed $value
     */
    abstract protected function magicSet($key, $value);

    /**
     * Is inner item exists?
     *
     * @param string $key
     * @return boolean
     */
    abstract protected function magicIsset($key);

    /**
     * Remove inner item
     *
     * @param string $key
     */
    abstract protected function magicUnset($key);
}
