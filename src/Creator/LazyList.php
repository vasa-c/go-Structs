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
     * Create instance by settings from array
     *
     * @param array $settings
     * @return \go\Structs\Creator\LazyList
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public static function createFromSettings(array $settings)
    {
        $name = isset($settings['name']) ? $settings['name'] : 'LazyList';
        if (!isset($settings['specs'])) {
            throw new \go\Structs\Exceptions\ConfigFormat($name, 'Not found "specs" in settings');
        }
        $specs = $settings['specs'];
        if (!\is_array($specs)) {
            throw new \go\Structs\Exceptions\ConfigFormat($name, 'Settings.specs must be array');
        }
        $namespace = isset($settings['namespace']) ? $settings['namespace'] : '';
        $up = true;
        if (isset($settings['default_args'])) {
            $dargs = $settings['default_args'];
            if (!\is_array($dargs)) {
                throw new \go\Structs\Exceptions\ConfigFormat($name, 'Settings.default_args must be array');
            }
            $up = false;
        } elseif (isset($settings['default_params'])) {
            $dargs = array($settings['default_params']);
        } else {
            $dargs = array();
        }
        if (isset($settings['up'])) {
            $up = $settings['up'];
        }
        return new self($specs, $namespace, $dargs, $up, $name);
    }

    /**
     * Constructor
     *
     * @param array $specs
     *        list of specifications
     * @param stirng $ns [optional]
     *        basic namespace
     * @param array $dargs [optional]
     *        default arguments for constructors
     * @param boolean $up [optional]
     *        use second element in format [classname, args] as params
     * @param string $name [optional
     *        name for debug and exception message
     */
    public function __construct(array $specs, $ns = null, array $dargs = null, $up = true, $name = null)
    {
        $this->specs = $specs;
        $this->factory = new Factory($ns, $dargs, $up);
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
     * @return boolean
     */
    public function getUP()
    {
        return $this->factory->getUP();
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
