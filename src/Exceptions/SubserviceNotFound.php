<?php
/**
 * Error: subservice is not found in aggregator
 *
 * @package go\Request
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Exceptions;

class SubserviceNotFound extends NotFound
{
    /**
     * @override \go\Structs\Exceptions\NotFound
     *
     * @return string
     */
    protected function createMessage()
    {
        return 'Subservice "'.$this->child.'" is not found in "'.$this->container.'"';
    }
}
