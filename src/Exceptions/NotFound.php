<?php
/**
 * Error: child is not found in container
 *
 * @package go\Request
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Exceptions;

abstract class NotFound extends Logic
{
    /**
     * Constructor
     *
     * @param string $container
     * @param string $child
     */
    public function __construct($container, $child)
    {
        $this->container = $container;
        $this->child = $child;
        parent::__construct($this->createMessage());
    }

    /**
     * @return string
     */
    final public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return string
     */
    final public function getChild()
    {
        return $this->child;
    }

    /**
     * Create message for constructor
     *
     * @return string
     */
    abstract protected function createMessage();

    /**
     * @var string
     */
    protected $container;

    /**
     * @var string
     */
    protected $child;
}
