<?php

namespace go\Tests\Structs\mocks\Context;

class First implements \ArrayAccess
{
    public $pone = 'first:one';

    public $ptwo = 'first:two';

    public function one()
    {
        return 'first:one('.\implode(',', \func_get_args()).')';
    }

    public function two()
    {
        return 'first:two('.\implode(',', \func_get_args()).')';
    }

    public function offsetExists($offset)
    {
        return ($offset === 'paa');
    }

    public function offsetGet($offset)
    {
        return ($offset === 'paa') ? 'first:aa' : null;
    }

    public function offsetSet($offset, $value)
    {
    }

    public function offsetUnset($offset)
    {
    }
}
