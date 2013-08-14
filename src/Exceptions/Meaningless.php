<?php
/**
 * Error: operation is this context is meaningless
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Exceptions;

class Meaningless extends Logic
{
    public function __construct($message = null)
    {
        if (\is_null($message)) {
            $message = 'Operation is this context is meaningless';
        }
        parent::__construct($message);
    }
}
