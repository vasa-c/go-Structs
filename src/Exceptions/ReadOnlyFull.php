<?php
/**
 * Error: object is read-only
 *
 * @package go\Request
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Exceptions;

class ReadOnlyFull extends ReadOnly
{
    /**
     * @override \go\Structs\Exception\ReadOnly
     */
    protected function createMessage()
    {
        return 'Instance of '.$this->service.' is read-only';
    }
}
