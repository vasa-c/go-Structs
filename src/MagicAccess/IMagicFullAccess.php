<?php
/**
 * Interface: access to inner values as properties of object and as array items
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\MagicAccess;

interface IMagicFullAccess extends IMagicPropsAccess, \ArrayAccess
{
}
