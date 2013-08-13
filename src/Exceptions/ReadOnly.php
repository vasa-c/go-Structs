<?php
/**
 * Error: set property is forbidden
 *
 * @package go\Request
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Exceptions;

abstract class ReadOnly extends Logic
{
    /**
     * Constructor
     *
     * @param string $service
     * @param string $property [optional]
     */
    public function __construct($service, $property = null)
    {
        $this->service = $service;
        $this->property = $property;
        parent::__construct($this->createMessage());
    }

    /**
     * @return string
     */
    final public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    final public function getProperty()
    {
        return $this->property;
    }

    /**
     * Create message for construct
     */
    abstract protected function createMessage();

    /**
     * @var string
     */
    protected $service;

    /**
     * @var string
     */
    protected $property;
}
