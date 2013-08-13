<?php
/**
 * Interface: access to inner values as properties of object
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\MagicAccess;

interface IMagicPropsAccess
{
    public function __get($key);

    public function __set($key, $value);

    public function __isset($key);

    public function __unset($key);
}
