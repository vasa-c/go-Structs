<?php
/**
 * Basic class for test of MagicArray and MagicArrayReadOnly class
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\MagicAccess;

abstract class MagicArrayBase extends \PHPUnit_Framework_TestCase
{
    protected $array = array(
        'one' => 'x',
        'two' => 'y',
        'three' => 'z',
    );

    abstract protected function createMagic(array $array = null);

    public function testGet()
    {
        $magic = $this->createMagic();
        $this->assertEquals('x', $magic->one);
        $this->assertEquals('y', $magic['two']);
        $this->assertNull($magic->four);
        $this->assertNull($magic['five']);
    }

    public function testIsset()
    {
        $magic = $this->createMagic();
        $this->assertTrue(isset($magic->two));
        $this->assertTrue(isset($magic['one']));
        $this->assertFalse(isset($magic->five));
        $this->assertFalse(isset($magic['four']));
    }

    public function testIssetNull()
    {
        $array = array(
            'a' => 1,
            'b' => null,
        );
        $magic = $this->createMagic($array);
        $this->assertTrue(isset($magic->a));
        $this->assertTrue(isset($magic->b));
        $this->assertTrue(isset($magic['b']));
        $this->assertFalse(isset($magic->c));
    }
}
