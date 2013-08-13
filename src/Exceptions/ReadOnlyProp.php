<?php
/**
 * Error: property is read-only
 *
 * @package go\Request
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Exceptions;

class ReadOnlyProp extends ReadOnly
{
    /**
     * @override \go\Structs\Exception\ReadOnly
     */
    protected function createMessage()
    {
        return $this->service.'->'.$this->property.' is read-only';
    }
}
