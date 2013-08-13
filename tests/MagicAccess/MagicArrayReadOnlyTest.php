<?php
/**
 * Test of MagicArrayReadOnly class
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\MagicAccess;

use go\Structs\MagicAccess\MagicArrayReadOnly;

/**
 * @covers go\Structs\MagicAccess\MagicArray
 */
class MagicArrayReadOnlyTest extends MagicArrayBase
{
    /**
     * @param array $array [optional]
     * @return \go\Structs\MagicAccess\MagicArrayReadOnly
     */
    protected function createMagic(array $array = null)
    {
        return new MagicArrayReadOnly($array ?: $this->array);
    }

    /**
     * @dataProvider providerSet
     * @param callable $callback
     * @expectedException \go\Structs\Exceptions\ReadOnlyFull
     */
    public function testSet($callback)
    {
        \call_user_func($callback, $this->createMagic());
    }

    /**
     * @return array
     */
    public function providerSet()
    {
        return array(
            array(
                function ($magic) {
                    $magic->one = 'one';
                }
            ),
            array(
                function ($magic) {
                    $magic['two'] = 'two';
                }
            ),
            array(
                function ($magic) {
                    $magic->four = 'four';
                }
            ),
            array(
                function ($magic) {
                    $magic['five'] = 'five';
                }
            ),
        );
    }

    /**
     * @dataProvider providerUnset
     * @param callable $callback
     * @expectedException \go\Structs\Exceptions\ReadOnlyFull
     */
    public function testUnset($callback)
    {
        \call_user_func($callback, $this->createMagic());
    }

    /**
     * @return array
     */
    public function providerUnset()
    {
        return array(
            array(
                function ($magic) {
                    unset($magic->one);
                }
            ),
            array(
                function ($magic) {
                    unset($magic['two']);
                }
            ),
            array(
                function ($magic) {
                    unset($magic->four);
                }
            ),
            array(
                function ($magic) {
                    unset($magic['five']);
                }
            ),
        );
    }
}
