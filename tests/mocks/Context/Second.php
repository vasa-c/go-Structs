<?php

namespace go\Tests\Structs\mocks\Context;

/**
 * @property $ptwo
 * @property $pthree
 */
class Second
{
    public function two()
    {
        return 'second:two('.\implode(',', \func_get_args()).')';
    }

    public function three()
    {
        return 'second:three('.\implode(',', \func_get_args()).')';
    }

    public function __isset($key)
    {
        return (($key === 'ptwo') || ($key === 'pthree'));
    }

    public function __get($key)
    {
        switch ($key) {
            case 'ptwo':
                return 'second:two';
            case 'pthree':
                return 'second:three';
        }
    }
}
