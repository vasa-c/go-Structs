<?php
/**
 * Error: object is read-only
 *
 * @package go\Request
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Exceptions;

class ReadOnly extends Logic
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
        $message = 'Instance of '.$service.' is read-only';
        parent::__construct($message);
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
     * @var string
     */
    protected $service;

    /**
     * @var string
     */
    protected $property;
}
