<?php
/**
 * Error: property is not found in container
 *
 * @package go\Request
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Exceptions;

class PropNotFound extends NotFound
{
    /**
     * @override \go\Structs\Exceptions\NotFound
     *
     * @return string
     */
    protected function createMessage()
    {
        return $this->container.'->'.$this->child.' is not found';
    }
}
