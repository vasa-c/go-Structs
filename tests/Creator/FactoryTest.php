<?php
/**
 * Test Creator factory
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\Creator;

use go\Structs\Creator\Factory;

/**
 * @covers go\Structs\Creator\Factory
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\Structs\Creator\Factory::__construct
     * @covers go\Structs\Creator\Factory::getBasicNamespace
     * @covers go\Structs\Creator\Factory::getDefaultArgs
     * @covers go\Structs\Creator\Factory::create
     * @covers go\Structs\Creator\Factory::listCreate
     * @covers go\Structs\Creator\Factory::__invoke
     */
    public function testFactory()
    {
        $factory = new Factory('go\Tests\Structs\Creator\mocks', array(1, 2));
        $this->assertEquals('go\Tests\Structs\Creator\mocks', $factory->getBasicNamespace());
        $this->assertEquals(array(1, 2), $factory->getDefaultArgs());
        $specs = array(
            'a' => '\go\Tests\Structs\Creator\mocks\Create',
            'b' => 'Create',
            'c' => array('Create'),
            'd' => array('Create', array(3, 4)),
            'e' => array('Create', array()),
            'f' => array('classname' => 'Create', 'args' => array(3, 4)),
        );
        $expected = array(
            'a' => array(1, 2),
            'b' => array(1, 2),
            'c' => array(),
            'd' => array(3, 4),
            'e' => array(),
            'f' => array(3, 4),
        );
        $result = $factory->listCreate($specs);
        foreach ($specs as $k => $spec) {
            $object1 = $factory->create($spec);
            $this->assertInstanceOf('go\Tests\Structs\Creator\mocks\Create', $object1);
            $this->assertEquals($expected[$k], $object1->getArgs());
            $object2 = $factory($spec);
            $this->assertInstanceOf('go\Tests\Structs\Creator\mocks\Create', $object2);
            $this->assertEquals($expected[$k], $object2->getArgs());
            $this->assertArrayHasKey($k, $result);
            $object3 = $result[$k];
            $this->assertInstanceOf('go\Tests\Structs\Creator\mocks\Create', $object3);
            $this->assertEquals($expected[$k], $object3->getArgs());
        }
    }
}
