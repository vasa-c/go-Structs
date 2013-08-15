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
     * @param array $cargs [optional]
     */
    public function __construct($namespace = null, array $cargs = null)
    {
        $this->namespace = $namespace;
        $this->cargs = $cargs;
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
     * Get constructor arguments
     *
     * @return array
     */
    public function getConstructArgs()
    {
        return $this->cargs;
    }

    /**
     * Create object by specification
     *
     * @param mixed $spec
     * @throws \go\Structs\Exceptions\ConfigFormat
     */
    public function create($spec)
    {
        return Creator::create($spec, $this->namespace, $this->cargs);
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
        return Creator::listCreate($specs, $this->namespace, $this->cargs);
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
        return Creator::create($spec, $this->namespace, $this->cargs);
    }

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var array
     */
    protected $cargs;
}
