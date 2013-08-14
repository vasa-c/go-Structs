<?php
/**
 * Test Registry
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs;

use go\Structs\Registry;

/**
 * @covers go\Structs\Registry
 */
class RegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\Structs\Registry::__construct
     * @covers go\Structs\Registry::setVar
     * @covers go\Structs\Registry::getVar
     */
    public function testGetSet()
    {
        $vars = array(
            'one' => 'First',
            'two' => 'Second',
        );
        $registry = new Registry($vars);
        $this->assertSame('First', $registry->getVar('one'));
        $this->assertSame('Second', $registry->getVar('two'));
        $this->assertSame('Second', $registry->getVar('two', 'Qwerty'));
        $this->assertSame(null, $registry->getVar('three'));
        $this->assertSame('Third', $registry->getVar('three', 'Third'));
        $registry->setVar('one', 'new one');
        $registry->setVar('three', 'new three');
        $this->assertSame('new one', $registry->getVar('one'));
        $this->assertSame('new three', $registry->getVar('three', 'Third'));
    }

    public function testMagicAccess()
    {
        $vars = array(
            'one' => 'First',
            'two' => 'Second',
        );
        $registry = new Registry($vars);
        $this->assertTrue(isset($registry->one));
        $this->assertSame('First', $registry->one);
        $this->assertFalse(isset($registry->three));
        $this->assertSame(null, $registry->three);
        unset($registry->one);
        $this->assertFalse(isset($registry->one));
        $this->assertSame(null, $registry->one);
        $registry->three = 'Third';
        $this->assertTrue(isset($registry->three));
        $this->assertSame('Third', $registry->three);
    }

    public function testArrayAccess()
    {
        $vars = array(
            'one' => 'First',
            'two' => 'Second',
        );
        $registry = new Registry($vars);
        $this->assertTrue(isset($registry['one']));
        $this->assertSame('First', $registry['one']);
        $this->assertFalse(isset($registry['three']));
        $this->assertSame(null, $registry['three']);
        unset($registry['one']);
        $this->assertFalse(isset($registry['one']));
        $this->assertSame(null, $registry['one']);
        $registry->three = 'Third';
        $this->assertTrue(isset($registry['three']));
        $this->assertSame('Third', $registry['three']);
    }

    /**
     * @covers go\Structs\Registry::__construct
     * @covers go\Structs\Registry::isReadOnly
     * @covers go\Structs\Registry::setReadOnly
     */
    public function testReadonlyFlag()
    {
        $vars = array(
            'one' => 'First',
            'two' => 'Second',
        );
        $registry = new Registry($vars);
        $this->assertFalse($registry->isReadOnly());
        $registry->toReadOnly();
        $this->assertTrue($registry->isReadOnly());

        $registry2 = new Registry($vars, null, true);
        $this->assertTrue($registry2->isReadOnly());
    }

    /**
     * @dataProvider providerReadonlyError
     * @expectedException \go\Structs\Exceptions\ReadOnlyFull
     * @param callable $callback
     */
    public function testReadonlyError($callback)
    {
        $vars = array(
            'one' => 'First',
            'two' => 'Second',
        );
        $registry = new Registry($vars, null, true);
        \call_user_func($callback, $registry);
    }

    /**
     * @return array
     */
    public function providerReadonlyError()
    {
        return array(
            array(
                function ($registry) {
                    $registry->setVar('one', 'newvalue');
                }
            ),
            array(
                function ($registry) {
                    $registry->two = 'new value';
                }
            ),
            array(
                function ($registry) {
                    $registry['three'] = 'new value';
                }
            ),
            array(
                function ($registry) {
                    unset($registry->one);
                }
            ),
            array(
                function ($registry) {
                    unset($registry->two);
                }
            ),
        );
    }

    public function testLazy()
    {
        $calls = array();
        $creator = function ($key) use (&$calls) {
            $calls[] = $key;
            return 'L'.$key;
        };
        $vars = array(
            'one' => 'First',
            'two' => 'Second',
        );
        $lazy = array(
            'three' => $creator,
            'four' => $creator,
        );
        $registry = new Registry($vars, $lazy);
        $this->assertTrue(isset($registry->one));
        $this->assertTrue(isset($registry->three));
        $this->assertFalse(isset($registry->five));
        $this->assertSame('First', $registry->getLoadedVar('one'));
        $this->assertSame(null, $registry->getLoadedVar('three'));
        $this->assertEmpty($calls);
        $this->assertSame('First', $registry->getVar('one'));
        $this->assertSame('Second', $registry['two']);
        $this->assertSame('Lthree', $registry->getVar('three'));
        $this->assertSame('Lfour', $registry->four);
        $this->assertNull($registry->five);
        $this->assertSame('Lthree', $registry->three);
        $this->assertSame('Lfour', $registry['four']);
        $this->assertSame('Lthree', $registry->getLoadedVar('three'));
        $this->assertEquals(array('three', 'four'), $calls);
    }

    public function testConstant()
    {
        $vars = array(
            'one' => 'First',
            'two' => 'Two',
        );
        $creator = function ($key) use (&$calls) {
            $calls[] = $key;
        };
        $lazy = array(
            'three' => $creator,
            'four' => $creator,
        );
        $registry = new Registry($vars, $lazy);
        $registry->markAsConstant('one');
        $registry->markAsConstant('three');
        $registry->markAsConstant('five');
        $this->assertTrue($registry->isConstant('one'));
        $this->assertFalse($registry->isConstant('two'));
        $this->assertTrue($registry->isConstant('three'));
        $this->assertFalse($registry->isConstant('four'));
        $this->assertFalse($registry->isConstant('five'));

        $this->four = 'new value';
        $this->setExpectedException('go\Structs\Exceptions\ReadOnlyProp');
        $registry->three = 'new value';
    }

    public function testConstantUnsetError()
    {
        $vars = array(
            'one' => 'First',
        );
        $registry = new Registry($vars);
        $registry->setVar('two', 'Second', true);
        $this->assertTrue(isset($registry->two));
        $this->assertTrue($registry->isConstant('two'));
        unset($registry->one);
        unset($registry->three);
        $this->setExpectedException('go\Structs\Exceptions\ReadOnlyProp');
        unset($registry->two);
    }

    public function testGetAllVars()
    {
        $registry = new Registry();
        $registry->one = 'First';
        $creator = function () {
            return 'lazy';
        };
        $registry->setLazyVar('two', $creator);
        $expectedLoaded = array(
            'one' => 'First',
        );
        $expectedAll = array(
            'one' => 'First',
            'two' => 'lazy',
        );
        $this->assertEquals($expectedLoaded, $registry->getAllVars(false));
        $this->assertEquals($expectedAll, $registry->getAllVars());
        $this->assertEquals($expectedAll, $registry->getAllVars(false));
    }

    public function testCountable()
    {
        $registry = new Registry();
        $registry->one = 'First';
        $creator = function () {
            return 'lazy';
        };
        $registry->setLazyVar('two', $creator);
        $this->assertCount(2, $registry);
    }

    public function testIterator()
    {
        $registry = new Registry();
        $registry->one = 'First';
        $creator = function () {
            return 'lazy';
        };
        $registry->setLazyVar('two', $creator);
        $expected = array(
            'one' => 'First',
            'two' => 'lazy',
        );
        $actual = array();
        foreach ($registry as $k => $v) {
            $actual[$k] = $v;
        }
        $this->assertEquals($expected, $actual);
    }
}
