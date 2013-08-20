<?php
/**
 * Stack of contexts
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs;

class Context implements \ArrayAccess
{
    /**
     * Constructor
     *
     * @param mixed $vars
     *        top context
     * @param mixed $parent [optional]
     *        parent context
     */
    public function __construct($vars, $parent = null)
    {
        if (\is_array($vars) || \is_object($vars)) {
            $this->vars = $vars;
        } else {
            throw new \InvalidArgumentException('Vars for context must be array or object');
        }
        if (\is_null($parent)) {
            $this->parent = null;
        } elseif (\is_array($parent) || \is_object($parent)) {
            if (!($parent instanceof self)) {
                $parent = new self($parent, null);
            }
            $this->parent = $parent;
        } else {
            throw new \InvalidArgumentException('Parent for context must be array or object');
        }
    }

    /**
     * Get var by name
     *
     * @param string $name
     * @param array $args [optional]
     *        arguments for method call
     * @param mixed $default [optional]
     * @return mixed
     */
    public function get($name, array $args = null, $default = null)
    {
        $vars = $this->vars;
        if (\is_object($vars)) {
            if ($vars instanceof self) {
                if ($vars->exists($name)) {
                    return $vars->get($name, $args);
                }
            } elseif (($vars instanceof \ArrayAccess) && isset($vars[$name])) {
                return $vars[$name];
            } elseif (isset($vars->$name)) {
                return $vars->$name;
            } elseif (\method_exists($vars, $name)) {
                return \call_user_func_array(array($vars, $name), $args ?: array());
            }
        } elseif (isset($vars[$name])) {
            return $vars[$name];
        }
        if ($this->parent) {
            return $this->parent->get($name, $args, $default);
        }
        return $default;
    }

    /**
     * Is var exists?
     *
     * @param string $name
     * @return boolean
     */
    public function exists($name)
    {
        $vars = $this->vars;
        if (\is_object($vars)) {
            if ($vars instanceof self) {
                if ($vars->exists($name)) {
                    return true;
                }
            } elseif (($vars instanceof \ArrayAccess) && isset($vars[$name])) {
                return true;
            } elseif (isset($vars->$name)) {
                return true;
            } elseif (\method_exists($vars, $name)) {
                return true;
            }
        } elseif (isset($vars[$name])) {
            return true;
        }
        if ($this->parent) {
            return $this->parent->exists($name);
        }
        return false;
    }

    /**
     * Is method exists?
     *
     * @param string $name
     * @return boolean
     */
    public function methodExists($name)
    {
        $vars = $this->vars;
        if (\is_object($vars)) {
            if ($vars instanceof self) {
                if ($vars->methodExists($name)) {
                    return true;
                }
            } elseif (\method_exists($vars, $name)) {
                return true;
            }
        }
        if ($this->parent) {
            return $this->parent->methodExists($name);
        }
        return false;
    }

    /**
     * Call method
     *
     * @param string $name
     * @param array $argus
     * @param mixed $default
     * @return mixed
     */
    public function callMethod($name, array $args = null, $default = null)
    {
        $vars = $this->vars;
        if (\is_object($vars)) {
            if ($vars instanceof self) {
                return $vars->callMethod($name, $args, $default);
            } elseif (\method_exists($vars, $name)) {
                return \call_user_func_array(array($vars, $name), $args ?: array());
            }
        }
        if ($this->parent) {
            return $this->parent->callMethod($name, $args, $default);
        }
        return $default;
    }

    /**
     * Magic get
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Magic isset
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return $this->exists($key);
    }

    /**
     * Magic set (forbidden)
     *
     * @param string $key
     * @param mixed $value
     * @throws \go\Structs\Exceptions\ReadOnlyFull
     */
    public function __set($key, $value)
    {
        throw new Exceptions\ReadOnlyFull('Context', $key);
    }

    /**
     * Magic unset (forbidden)
     *
     * @param string $key
     * @throws \go\Structs\Exceptions\ReadOnlyFull
     */
    public function __unset($key)
    {
        throw new Exceptions\ReadOnlyFull('Context', $key);
    }

    /**
     * Magic call
     *
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        return $this->callMethod($name, $args);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @override \ArrayAccess (forbidden)
     *
     * @param string $offset
     * @return boolean
     * @throws \go\Structs\Exceptions\ReadOnlyFull
     */
    public function offsetSet($offset, $value)
    {
        throw new Exceptions\ReadOnlyFull('Context', $offset);
    }

    /**
     * @override \ArrayAccess (forbidden)
     *
     * @param string $offset
     * @return boolean
     * @throws \go\Structs\Exceptions\ReadOnlyFull
     */
    public function offsetUnset($offset)
    {
        throw new Exceptions\ReadOnlyFull('Context', $offset);
    }

    /**
     * @var array
     */
    protected $vars;

    /**
     * @var \go\Structs\Context
     */
    protected $parent;
}
