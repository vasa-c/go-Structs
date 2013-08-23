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
     * @param string $namespace [optional]
     *        basic namespace
     * @param array $cargs [optional]
     *        arguments for constructor
     * @return object
     *         created object
     * @throws \go\Structs\Exceptions\ConfigFormat
     *         error format of specification
     */
    public static function create($spec, $namespace = null, array $cargs = null)
    {
        if (\is_object($spec)) {
            return $spec;
        }
        if (\is_string($spec)) {
            return self::createByClassname($spec, $namespace, $cargs);
        }
        if (!\is_array($spec)) {
            throw new ConfigFormat('Creator', 'error type of spec: '.\gettype($spec));
        }
        if (isset($spec[0])) {
            $args = isset($spec[1]) ? $spec[1] : $cargs;
            return self::createByClassname($spec[0], $namespace, $args);
        }
        if (isset($spec['classname'])) {
            $args = self::createArgs($spec, $cargs);
            return self::createByClassname($spec['classname'], $namespace, $args);
        }
        if (isset($spec['creator'])) {
            $creator = $spec['creator'];
            $args = self::createArgs($spec, $cargs);
            return \call_user_func_array($creator, $args);
        }
        throw new ConfigFormat('Creator', 'classname or creator is not found');
    }

    /**
     * Create list objects by list specs
     *
     * @param array $specs
     * @param string $namespace [optional]
     * @param array $cargs [optional]
     * @return array
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public static function listCreate(array $specs, $namespace = null, array $cargs = null)
    {
        $result = array();
        foreach ($specs as $k => $spec) {
            $result[$k] = self::create($spec, $namespace, $cargs);
        }
        return $result;
    }

    /**
     * Create factory
     *
     * @param string $namespace [optional]
     * @param array $cargs [optional]
     * @return \go\Structs\Creator\Factory
     */
    public static function getFactory($namespace = null, array $cargs = null)
    {
        return new Factory($namespace, $cargs);
    }

    /**
     * Create arguments list
     *
     * @param array $spec
     * @param array $cargs
     * @return array
     */
    private static function createArgs(array $spec, array $cargs)
    {
        if (isset($spec['params'])) {
            return array($spec['params']);
        } elseif (isset($spec['args'])) {
            if (!\is_array($spec['args'])) {
                throw new ConfigFormat('Creator', 'args must be array');
            }
            return $spec['args'];
        } else {
            return $cargs ?: array();
        }
    }

    /**
     * Create instance by relative classname
     *
     * @param string $classname
     * @param string $namespace
     * @param array $args
     */
    private static function createByClassname($classname, $namespace, $args)
    {
        if (empty($classname)) {
            throw new ConfigFormat('Creator', 'classname is empty');
        }
        if (\strpos($classname, '\\') !== 0) {
            $classname = $namespace.'\\'.$classname;
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
