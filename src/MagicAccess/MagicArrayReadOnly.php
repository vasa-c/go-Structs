<?php
/**
 * Magic read-only array
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\MagicAccess;

class MagicArrayReadOnly extends MagicArray
{
    protected $magicReadOnly = true;
}
