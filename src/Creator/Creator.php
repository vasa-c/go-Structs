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
            $classname = $spec;
            $args = null;
        } elseif (\is_array($spec)) {
            if (isset($spec[0])) {
                $classname = $spec[0];
                $args = isset($spec[1]) ? $spec[1] : null;
            } elseif (isset($spec['classname'])) {
                $classname = $spec['classname'];
                $args = isset($spec['args']) ? $spec['args'] : null;
            } elseif (isset($spec['creator'])) {
                $creator = $spec['creator'];
                $args = isset($spec['args']) ? $spec['args'] : $cargs;
                if (!\is_array($args)) {
                    $args = array();
                }
                return \call_user_func_array($creator, $args);
            } else {
                throw new ConfigFormat('Creator');
            }
        } else {
            throw new ConfigFormat('Creator', 'error type of spec - '.\gettype($spec));
        }
        if (empty($classname)) {
            throw new ConfigFormat('Creator', 'classname is empty');
        }
        $classname = self::createFullClassname($classname, $namespace);
        if (!\class_exists($classname, true)) {
            throw new ConfigFormat('Creator', 'class '.$classname.' is not found');
        }
        $args = \is_array($args) ? $args : $cargs;
        if (empty($args)) {
            return new $classname;
        } else {
            $rclass = new \ReflectionClass($classname);
            return $rclass->newInstanceArgs($args);
        }
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
     * @param string $classname
     * @param string $namespace
     * @return string
     */
    private static function createFullClassname($classname, $namespace)
    {
        if (\strpos($classname, '\\') === 0) {
            return $classname;
        }
        return $namespace.'\\'.$classname;
    }
}
