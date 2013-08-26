<?php
/**
 * Factory for creating objects
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Creator;

class Factory
{
    /**
     * Constructor
     *
     * @param string $ns [optional]
     * @param array $dargs [optional]
     * @param boolean $up [optional]
     */
    public function __construct($ns = null, array $dargs = null, $up = true)
    {
        $this->namespace = $ns;
        $this->dargs = $dargs ?: array();
        $this->up = $up;
    }

    /**
     * Get basic namespace
     *
     * @return string
     */
    public function getBasicNamespace()
    {
        return $this->namespace;
    }

    /**
     * Get default arguments
     *
     * @return array
     */
    public function getDefaultArgs()
    {
        return $this->dargs;
    }

    /**
     * @return boolean
     */
    public function getUP()
    {
        return $this->up;
    }

    /**
     * Create object by specification
     *
     * @param mixed $spec
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public function create($spec)
    {
        return Creator::create($spec, $this->namespace, $this->dargs, $this->up);
    }

    /**
     * Create list objects by list specs
     *
     * @param array $specs
     * @return array
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public function listCreate(array $specs)
    {
        return Creator::listCreate($specs, $this->namespace, $this->dargs, $this->up);
    }

    /**
     * Magic invoke
     *
     * @param mixed $spec
     * @return object
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public function __invoke($spec)
    {
        return Creator::create($spec, $this->namespace, $this->dargs, $this->up);
    }

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var array
     */
    protected $dargs;

    /**
     * @var boolean
     */
    protected $up;
}
