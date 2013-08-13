<?php
/**
 * Test of MagicArray class
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\MagicAccess;

use go\Structs\MagicAccess\MagicArray;

/**
 * @covers go\Structs\MagicAccess\MagicArray
 */
class MagicArrayTest extends MagicArrayBase
{
    /**
     * @param array $array [optional]
     * @return \go\Structs\MagicAccess\MagicArray
     */
    protected function createMagic(array $array = null)
    {
        return new MagicArray($array ?: $this->array);
    }

    public function testSet()
    {
        $magic = $this->createMagic();
        $magic->one = '111';
        $magic['two'] = '222';
        $magic['four'] = '444';
        $magic->five = '555';
        $this->assertTrue(isset($magic->two));
        $this->assertTrue(isset($magic->five));
        $this->assertEquals('444', $magic->four);
        $this->assertEquals('111', $magic['one']);
    }

    public function testUnset()
    {
        $magic = $this->createMagic();
        unset($magic->one);
        unset($magic['two']);
        unset($magic['four']);
        unset($magic->five);
        $this->assertFalse(isset($magic->two));
        $this->assertNull($magic['one']);
        $this->assertFalse(isset($magic['five']));
        $this->assertNull($magic->four);
        $this->assertTrue(isset($magic->three));
        $this->assertEquals('z', $magic['three']);
    }
}
