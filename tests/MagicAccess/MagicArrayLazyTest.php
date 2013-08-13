<?php
/**
 * Test of MagicArrayLazy class
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\MagicAccess;

use go\Structs\MagicAccess\MagicArrayLazy;

/**
 * @covers go\Structs\MagicAccess\MagicArrayLazy
 */
class MagicArrayLazyTest extends \PHPUnit_Framework_TestCase
{

    public function testLazy()
    {
        $array = array(
            'one' => 'One',
            'two' => 'Two',
        );
        $calls = array();
        $lazy = array(
            'two' => function ($key) use (&$calls) {
                $calls[] = $key;
                return 'LTwo';
            },
            'three' => function ($key) use (&$calls) {
                $calls[] = $key;
                return 'LThree';
            },
        );
        $magic = new MagicArrayLazy($array, $lazy);
        $this->assertTrue(isset($magic->one));
        $this->assertTrue(isset($magic->two));
        $this->assertTrue(isset($magic->three));
        $this->assertFalse(isset($magic->four));
        $this->assertEmpty($calls);
        $this->assertEquals('One', $magic->one);
        $this->assertEquals('Two', $magic->two);
        $this->assertEquals('LThree', $magic->three);
        $this->assertNull($magic->four);
        $this->assertEquals('LThree', $magic->three);
        $this->assertEquals(array('three'), $calls);

        $calls = array();
        unset($magic->two);
        unset($magic->three);
        $this->assertNull($magic->two);
        $this->assertNull($magic->three);
        $this->assertFalse(isset($magic->three));
        $this->assertEmpty($calls);

        $creator = function ($key) use (&$calls) {
            $calls[] = '!'.$key;
            return 'L'.$key;
        };
        $magic->setItemCreator('three', $creator);
        $magic->setItemCreator('four', $creator);
        $this->assertTrue(isset($magic->three));
        $this->assertTrue(isset($magic->four));
        $this->assertEquals('Lfour', $magic->four);
        $this->assertEquals('Lthree', $magic->three);
        $this->assertEquals('Lthree', $magic->three);
        $this->assertEquals('Lfour', $magic->four);
        $this->assertEquals(array('!four', '!three'), $calls);
    }
}
