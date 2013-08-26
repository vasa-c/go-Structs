<?php
/**
 * Test LazyList class
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\Creator;

use go\Structs\Creator\LazyList;
use go\Tests\Structs\Creator\mocks\Create;

/**
 * @covers go\Structs\Creator\LazyList
 */
class LazyListTest extends \PHPUnit_Framework_TestCase
{
    protected $namespace = 'go\Tests\Structs\Creator\mocks';

    protected $classname = 'go\Tests\Structs\Creator\mocks\Create';

    protected $args = array('def');

    protected $up = false;

    protected $specs = array(
        'one' => array('\go\Tests\Structs\Creator\mocks\Create', array('one')),
        'two' => array('Create', array('two')),
        'def' => 'Create',
    );

    /**
     * @param boolean $resetCalls [optional]
     * @return \go\Structs\Creator\LazyList
     */
    protected function createLL($resetCalls = true)
    {
        if ($resetCalls) {
            Create::resetCalls();
        }
        return new LazyList($this->specs, $this->namespace, $this->args, $this->up, 'Test');
    }


    /**
     * @covers go\Structs\Creator\LazyList::getSpecs
     */
    public function testGetSpecs()
    {
        $ll = $this->createLL();
        $this->assertEquals($this->specs, $ll->getSpecs());
    }

    /**
     * @covers go\Structs\Creator\LazyList::getBasicNamespace
     */
    public function testGetBasicNamespace()
    {
        $ll = $this->createLL();
        $this->assertEquals($this->namespace, $ll->getBasicNamespace());
    }

    /**
     * @covers go\Structs\Creator\LazyList::getDefaultArgs
     */
    public function testGetDefaultArgs()
    {
        $ll = $this->createLL();
        $this->assertEquals($this->args, $ll->getDefaultArgs());
    }

    /**
     * @covers go\Structs\Creator\LazyList::getUP
     */
    public function testGetUP()
    {
        $ll = $this->createLL();
        $this->assertFalse($ll->getUP());
    }

    /**
     * @covers go\Structs\Creator\LazyList::getCreatorFactory
     */
    public function testGetCreatorFactory()
    {
        $ll = $this->createLL();
        $factory = $ll->getCreatorFactory();
        $this->assertInstanceOf('go\Structs\Creator\Factory', $factory);
        $this->assertEquals($this->namespace, $factory->getBasicNamespace());
        $this->assertEquals($this->args, $factory->getDefaultArgs());
    }

    /**
     * @covers go\Structs\Creator\LazyList::get
     */
    public function testGet()
    {
        $ll = $this->createLL();
        $one = $ll->get('one');
        $this->assertInstanceOf($this->classname, $one);
        $two = $ll->get('two');
        $this->assertInstanceOf($this->classname, $two);
        $this->assertSame($one, $ll->get('one'));
        $this->assertNull($ll->get('def', true));
        $def = $ll->get('def');
        $this->assertEquals(array('one', 'two', 'def'), Create::getCalls());
        $ll->get('two');
        $this->assertEquals(array('one', 'two', 'def'), Create::getCalls());
        $this->assertEquals(array('one'), $one->getArgs());
        $this->assertEquals(array('two'), $two->getArgs());
        $this->assertEquals(array('def'), $def->getArgs());
    }

    /**
     * @covers go\Structs\Creator\LazyList::get
     */
    public function testGetErrorFormat()
    {
        $specs = $this->specs;
        $specs['four'] = 123;
        $ll = new LazyList($specs);
        $ll->get('one');
        $ll->get('four', true);
        $this->setExpectedException('go\Structs\Exceptions\ConfigFormat');
        $ll->get('four');
    }

    /**
     * @covers go\Structs\Creator\LazyList::get
     */
    public function testGetErrorNotFound()
    {
        $ll = $this->createLL();
        $this->setExpectedException('go\Structs\Exceptions\SubserviceNotFound');
        $ll->get('four');
    }

    /**
     * @covers go\Structs\Creator\LazyList::exists
     */
    public function testExists()
    {
        $ll = $this->createLL();
        $ll->get('one');
        $this->assertTrue($ll->exists('one'));
        $this->assertTrue($ll->exists('two'));
        $this->assertFalse($ll->exists('unk'));
    }

    /**
     * @covers go\Structs\Creator\LazyList::isCreated
     */
    public function testIsCreated()
    {
        $ll = $this->createLL();
        $ll->get('one');
        $ll->get('one', true);
        $ll->get('two', true);
        $this->assertTrue($ll->isCreated('one'));
        $this->assertFalse($ll->isCreated('two'));
        $this->assertFalse($ll->isCreated('unk'));
    }

    /**
     * @covers go\Structs\Creator\LazyList::getAllObjects
     */
    public function testGetAllObjects()
    {
        $ll = $this->createLL();
        $one = $ll->get('one');
        $all = $ll->getAllObjects();
        $expected = array(
            'one' => $one,
            'two' => $ll->get('two'),
            'def' => $ll->get('def'),
        );
        $this->assertEquals($expected, $all);
        $this->assertEquals($expected, $ll->getAllObjects());
    }

    /**
     * @covers go\Structs\Creator\LazyList::getOnlyCreated
     */
    public function testGetOnlyCreated()
    {
        $ll = $this->createLL();
        $one = $ll->get('one');
        $expected = array(
            'one' => $one,
        );
        $this->assertEquals($expected, $ll->getOnlyCreated());
        $expected['def'] = $ll->get('def');
        $this->assertEquals($expected, $ll->getOnlyCreated());
    }

    /**
     * @covers go\Structs\Creator\LazyList::__get
     */
    public function testMagicGet()
    {
        $ll = $this->createLL();
        $one = $ll->one;
        $this->assertInstanceOf($this->classname, $one);
        $this->assertEquals(array('one'), $one->getArgs());
        $this->assertSame($one, $ll->get('one'));
    }

    /**
     * @covers go\Structs\Creator\LazyList::__get
     */
    public function testMagicGetErrorFormat()
    {
        $specs = $this->specs;
        $specs['four'] = 123;
        $ll = new LazyList($specs);
        $this->setExpectedException('go\Structs\Exceptions\ConfigFormat');
        return $ll->four;
    }

    /**
     * @covers go\Structs\Creator\LazyList::__get
     */
    public function testMagicGetErrorNotFound()
    {
        $ll = $this->createLL();
        $this->setExpectedException('go\Structs\Exceptions\SubserviceNotFound');
        return $ll->four;
    }

    /**
     * @covers go\Structs\Creator\LazyList::__isset
     */
    public function testMagicIsset()
    {
        $ll = $this->createLL();
        $ll->get('one');
        $this->assertTrue(isset($ll->one));
        $this->assertTrue(isset($ll->two));
        $this->assertFalse(isset($ll->unk));
    }

    /**
     * @covers go\Structs\Creator\LazyList::__set
     * @expectedException go\Structs\Exceptions\ReadOnlyFull
     */
    public function testSet()
    {
        $ll = $this->createLL();
        $ll->one = (object)(array(1));
    }

    /**
     * @covers go\Structs\Creator\LazyList::__unset
     * @expectedException go\Structs\Exceptions\ReadOnlyFull
     */
    public function testUnset()
    {
        $ll = $this->createLL();
        unset($ll->one);
    }

    /**
     * @covers go\Structs\Creator\LazyList::createAll
     */
    public function testCreateAll()
    {
        $ll = $this->createLL();
        $ll->get('one');
        $ll->createAll();
        $this->assertTrue($ll->isCreated('one'));
        $this->assertTrue($ll->isCreated('two'));
        $this->assertTrue($ll->isCreated('def'));
        $this->assertFalse($ll->isCreated('unk'));
    }
}
