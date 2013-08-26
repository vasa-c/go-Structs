<?php
/**
 * List of object with lazy loading
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c
 */

namespace go\Structs\Creator;

class LazyList
{
    /**
     * Constructor
     *
     * @param array $specs
     *        list of specifications
     * @param stirng $namespace [optional]
     *        basic namespace
     * @param array $dargs [optional]
     *        default arguments for constructors
     * @param string $name [optional
     *        name for debug and exception message
     */
    public function __construct(array $specs, $namespace = null, array $dargs = null, $name = null)
    {
        $this->specs = $specs;
        $this->factory = new Factory($namespace, $dargs);
        $this->name = $name ?: 'LazyList';
    }

    /**
     * Get list of specifications
     *
     * @return array
     */
    public function getSpecs()
    {
        return $this->specs;
    }

    /**
     * Get basic namespace
     *
     * @return string
     */
    public function getBasicNamespace()
    {
        return $this->factory->getBasicNamespace();
    }

    /**
     * Get default arguments
     *
     * @return array
     */
    public function getDefaultArgs()
    {
        return $this->factory->getDefaultArgs();
    }

    /**
     * Get factory for creating objects
     *
     * @return \go\Structs\Creator\Factory
     */
    public function getCreatorFactory()
    {
        return $this->factory;
    }

    /**
     * Get subservice
     *
     * @param string $key
     * @param boolean $created [optional]
     *        only if already created
     * @throws \go\Structs\Exceptions\PropNotFound
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public function get($key, $created = false)
    {
        if (!isset($this->objects[$key])) {
            if (!isset($this->specs[$key])) {
                throw new \go\Structs\Exceptions\SubserviceNotFound($this->name, $key);
            }
            if ($created) {
                return null;
            }
            $this->objects[$key] = $this->factory->create($this->specs[$key]);
        }
        return $this->objects[$key];
    }

    /**
     * Is key exists?
     *
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        return isset($this->specs[$key]);
    }

    /**
     * Is object created?
     *
     * @param string $key
     * @return boolean
     */
    public function isCreated($key)
    {
        return isset($this->objects[$key]);
    }

    /**
     * Get all objects (with forced creating)
     *
     * @return array
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public function getAllObjects()
    {
        $this->createAll();
        return $this->objects;
    }

    /**
     * Get objects that are already created
     *
     * @return array
     */
    public function getOnlyCreated()
    {
        return $this->objects;
    }

    /**
     * Magic get
     *
     * @param string $key
     * @return object
     * @throws \go\Structs\Exceptions\PropNotFound
     * @throws \go\Structs\Exceptions\ConfigFormat
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
        throw new \go\Structs\Exceptions\ReadOnlyFull($this->name, $key);
    }

    /**
     * Magic unset (forbidden)
     *
     * @param string $key
     * @param mixed $value
     * @throws \go\Structs\Exceptions\ReadOnlyFull
     */
    public function __unset($key)
    {
        throw new \go\Structs\Exceptions\ReadOnlyFull($this->name, $key);
    }

    /**
     * Creating all objects
     *
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public function createAll()
    {
        if (!$this->allCreated) {
            foreach ($this->specs as $k => $spec) {
                if (!isset($this->objects[$k])) {
                    $this->objects[$k] = $this->factory->create($spec);
                }
            }
            $this->allCreated = true;
        }
    }

    /**
     * @var array
     */
    protected $specs;

    /**
     * @var array
     */
    protected $objects = array();

    /**
     * @var \go\Structs\Creator\Factory
     */
    protected $factory;

    /**
     * @var boolean
     */
    protected $allCreated;

    /**
     * @var string
     */
    protected $name;
}
