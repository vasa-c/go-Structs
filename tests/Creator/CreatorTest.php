<?php
/**
 * Test Creator static methods
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\Creator;

use go\Structs\Creator\Creator;

/**
 * @covers go\Structs\Creator\Creator
 */
class CreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\Structs\Creator\Creator::create
     * @dataProvider providerCreate
     * @param mixed $spec
     * @param string $namespace
     * @param array $cargs
     * @param array $result (null - exception)
     */
    public function testCreate($spec, $namespace, $cargs, $result)
    {
        if (\is_null($result)) {
            $this->setExpectedException('go\Structs\Exceptions\ConfigFormat');
        }
        $actual = Creator::create($spec, $namespace, $cargs);
        if (!\is_null($result)) {
            $this->assertInstanceOf('go\Tests\Structs\Creator\mocks\Create', $actual);
            $this->assertEquals($result, $actual->getArgs());
        }
    }

    /**
     * @return array
     */
    public function providerCreate()
    {
        $cr = function () {
            return new \go\Tests\Structs\Creator\mocks\Create(\array_sum(\func_get_args()));
        };
        return array(
            array(
                new \go\Tests\Structs\Creator\mocks\Create(3, 4),
                'anything',
                array(1, 2, 3, 4),
                array(3, 4),
            ),
            array(
                '\go\Tests\Structs\Creator\mocks\Create',
                null,
                null,
                array(),
            ),
            array(
                'go\Tests\Structs\Creator\mocks\Create',
                null,
                null,
                array(),
            ),
            array(
                'mocks\Create',
                'go\Tests\Structs\Creator',
                null,
                array(),
            ),
            array(
                'Create',
                'go\Tests\Structs\Creator\mocks',
                array(1, 2),
                array(1, 2),
            ),
            array(
                array('Create', array(3, 4)),
                'go\Tests\Structs\Creator\mocks',
                array(1, 2),
                array(3, 4),
            ),
            array(
                array('Create', array()),
                'go\Tests\Structs\Creator\mocks',
                array(1, 2),
                array(),
            ),
            array(
                array('Create'),
                'go\Tests\Structs\Creator\mocks',
                array(1, 2),
                array(1, 2),
            ),
            array(
                array(
                    'classname' => 'Create',
                ),
                'go\Tests\Structs\Creator\mocks',
                array(1, 2),
                array(1, 2),
            ),
            array(
                array(
                    'classname' => '\go\Tests\Structs\Creator\mocks\Create',
                ),
                'go\Tests\Structs\Creator\mocks',
                array(1, 2),
                array(1, 2),
            ),
            array(
                array(
                    'classname' => 'go\Tests\Structs\Creator\mocks\Create',
                    'args' => array(3, 4),
                ),
                '',
                array(1, 2),
                array(3, 4),
            ),
            array(
                array(
                    'classname' => 'go\Tests\Structs\Creator\mocks\Create',
                    'args' => array(),
                ),
                '',
                array(1, 2),
                array(),
            ),
            array(
                array(
                    'classname' => 'go\Tests\Structs\Creator\mocks\Create',
                    'args' => 'single',
                ),
                '',
                array(1, 2),
                null,
            ),
            array(
                array(
                    'classname' => 'go\Tests\Structs\Creator\mocks\Create',
                    'params' => 'single',
                ),
                '',
                array(1, 2),
                array('single'),
            ),
            array(
                array(
                    'creator' => $cr,
                ),
                null,
                array(1, 2),
                array(3),
            ),
            array(
                array(
                    'creator' => $cr,
                    'args' => array(3, 4),
                ),
                null,
                array(1, 2),
                array(7),
            ),
            array(
                array(
                    'creator' => $cr,
                    'params' => 5,
                ),
                null,
                array(1, 2),
                array(5),
            ),
            array(
                array(
                    'args' => array(3, 4),
                ),
                null,
                array(1, 2),
                null,
            ),
            array(
                '',
                null,
                array(1, 2),
                null,
            ),
            array(
                123,
                null,
                array(1, 2),
                null,
            ),
            array(
                '\go\Tests\Structs\Creator\mocks\Unknown',
                null,
                array(1, 2),
                null,
            ),
            array(
                'go\Tests\Structs\Creator\mocks\Create',
                'go\Tests\Structs\Creator\mocks',
                null,
                null,
            ),
        );
    }

    /**
     * @covers go\Structs\Creator\Creator::listCreate
     */
    public function testListCreate()
    {
        $specs = array(
            'a' => '\go\Tests\Structs\Creator\mocks\Create',
            'b' => 'Create',
        );
        $actual = Creator::listCreate($specs, 'go\Tests\Structs\Creator\mocks');
        $this->assertInstanceOf('go\Tests\Structs\Creator\mocks\Create', $actual['a']);
        $this->assertInstanceOf('go\Tests\Structs\Creator\mocks\Create', $actual['b']);
    }

    /**
     * @covers go\Structs\Creator\Creator::getFactory
     */
    public function testGetFactory()
    {
        $factory = Creator::getFactory('go\Tests\Structs\Creator\mocks');
        $this->assertInstanceOf('go\Structs\Creator\Factory', $factory);
        $this->assertEquals('go\Tests\Structs\Creator\mocks', $factory->getBasicNamespace());
        $this->assertEquals(array(), $factory->getConstructArgs());
        $object = $factory->create(array('Create', array(5, 6, 7)));
        $this->assertInstanceOf('go\Tests\Structs\Creator\mocks\Create', $object);
        $this->assertEquals(array(5, 6, 7), $object->getArgs());
    }
}
