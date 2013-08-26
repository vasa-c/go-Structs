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
     * @param string $namespace [optional]
     * @param array $dargs [optional]
     */
    public function __construct($namespace = null, array $dargs = null)
    {
        $this->namespace = $namespace;
        $this->dargs = $dargs ?: array();
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
     * Create object by specification
     *
     * @param mixed $spec
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public function create($spec)
    {
        return Creator::create($spec, $this->namespace, $this->dargs);
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
        return Creator::listCreate($specs, $this->namespace, $this->dargs);
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
        return Creator::create($spec, $this->namespace, $this->dargs);
    }

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var array
     */
    protected $dargs;
}
