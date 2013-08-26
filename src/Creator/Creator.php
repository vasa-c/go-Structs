<?php
/**
 * Create object by params
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Creator;

use go\Structs\Exceptions\ConfigFormat;

class Creator
{
    /**
     * Create object by params
     *
     * @param mixed $spec
     *        specification of object
     * @param string $ns [optional]
     *        basic namespace
     * @param array $dargs [optional]
     *        default arguments for constructor
     * @param boolean $up [optional]
     *        use second element in format [classname, args] as params
     * @return object
     *         created object
     * @throws \go\Structs\Exceptions\ConfigFormat
     *         error format of specification
     */
    public static function create($spec, $ns = null, array $dargs = null, $up = true)
    {
        if (\is_object($spec)) {
            return $spec;
        }
        if (\is_string($spec)) {
            return self::createByClassname($spec, $ns, $dargs);
        }
        if (!\is_array($spec)) {
            throw new ConfigFormat('Creator', 'error type of spec: '.\gettype($spec));
        }
        if (isset($spec[0])) {
            if (\array_key_exists(1, $spec)) {
                $args = $spec[1];
                if ($up) {
                    $args = array($args);
                } elseif (!\is_array($args)) {
                    throw new ConfigFormat('Creator', 'args must be array');
                }
            } else {
                $args = array();
            }
            return self::createByClassname($spec[0], $ns, $args);
        }
        if (isset($spec['classname'])) {
            $args = self::createArgs($spec, $dargs);
            return self::createByClassname($spec['classname'], $ns, $args);
        }
        if (isset($spec['creator'])) {
            $creator = $spec['creator'];
            $args = self::createArgs($spec, $dargs);
            return \call_user_func_array($creator, $args);
        }
        throw new ConfigFormat('Creator', 'classname or creator is not found');
    }

    /**
     * Create list objects by list specs
     *
     * @param array $specs
     * @param string $ns [optional]
     * @param array $dargs [optional]
     * @param boolean $up [optional]
     * @return array
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public static function listCreate(array $specs, $ns = null, array $dargs = null, $up = true)
    {
        $result = array();
        foreach ($specs as $k => $spec) {
            $result[$k] = self::create($spec, $ns, $dargs, $up);
        }
        return $result;
    }

    /**
     * Create factory
     *
     * @param string $ns [optional]
     * @param array $dargs [optional]
     * @param boolean $up [optional]
     * @return \go\Structs\Creator\Factory
     */
    public static function getFactory($ns = null, array $dargs = null, $up = true)
    {
        return new Factory($ns, $dargs);
    }

    /**
     * Create arguments list
     *
     * @param array $spec
     * @param array $dargs
     * @return array
     */
    private static function createArgs(array $spec, array $dargs)
    {
        if (isset($spec['params'])) {
            return array($spec['params']);
        } elseif (isset($spec['args'])) {
            if (!\is_array($spec['args'])) {
                throw new ConfigFormat('Creator', 'args must be array');
            }
            return $spec['args'];
        } else {
            return $dargs ?: array();
        }
    }

    /**
     * Create instance by relative classname
     *
     * @param string $classname
     * @param string $ns
     * @param array $args
     */
    private static function createByClassname($classname, $ns, $args)
    {
        if (empty($classname)) {
            throw new ConfigFormat('Creator', 'classname is empty');
        }
        if (\strpos($classname, '\\') !== 0) {
            $classname = $ns.'\\'.$classname;
        }
        if (!\class_exists($classname, true)) {
            throw new ConfigFormat('Creator', 'class '.$classname.' is not found');
        }
        if (empty($args)) {
            return new $classname();
        } else {
            $class = new \ReflectionClass($classname);
            return $class->newInstanceArgs($args);
        }
    }
}
