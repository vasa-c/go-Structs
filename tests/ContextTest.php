<?php
/**
 * Test Context class
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs;

use go\Structs\Context;
use go\Tests\Structs\mocks\Context\First;
use go\Tests\Structs\mocks\Context\Second;

/**
 * @covers go\Structs\Context
 */
class ContextTest extends \PHPUnit_Framework_TestCase
{
    public function testContext()
    {
        $context1 = (object)array('a' => 'x', 'b' => 'y', 'c' => 'z');
        $context2 = new Context(new First(), $context1);
        $context3 = new Context(array('a' => 'ax', 'x' => 'a'), $context2);
        $context4 = new Context(new Second(), $context3);
        $context = new Context($context4);

        $this->assertTrue($context->exists('a'));
        $this->assertTrue($context->exists('b'));
        $this->assertTrue($context->exists('pone'));
        $this->assertTrue($context->exists('ptwo'));
        $this->assertTrue($context->exists('pthree'));
        $this->assertTrue($context->exists('paa'));
        $this->assertTrue($context->exists('one'));
        $this->assertTrue($context->exists('two'));
        $this->assertTrue($context->exists('three'));
        $this->assertFalse($context->exists('unk'));
        $this->assertFalse($context->exists('callMethod'));
        $this->assertTrue(isset($context->pone));
        $this->assertTrue(isset($context->one));
        $this->assertFalse(isset($context->unk));
        $this->assertTrue(isset($context['pone']));
        $this->assertTrue(isset($context['one']));
        $this->assertFalse(isset($context['unk']));

        $this->assertTrue($context->methodExists('one'));
        $this->assertFalse($context->methodExists('pone'));
        $this->assertFalse($context->methodExists('unk'));

        $this->assertSame('ax', $context->get('a'));
        $this->assertSame('y', $context->get('b'));
        $this->assertSame('y', $context->get('b', array(1, 2), 'def'));
        $this->assertSame(null, $context->get('unk'));
        $this->assertSame('def', $context->get('unk', null, 'def'));
        $this->assertSame('first:one', $context->get('pone'));
        $this->assertSame('second:two', $context->get('ptwo'));
        $this->assertSame('second:three', $context->get('pthree'));
        $this->assertSame('first:one()', $context->get('one'));
        $this->assertSame('first:one(1,2)', $context->get('one', array(1, 2)));
        $this->assertSame('second:two(1,2)', $context->get('two', array(1, 2)));
        $this->assertSame('second:three(1,2)', $context->get('three', array(1, 2)));
        $this->assertSame('ax', $context->a);
        $this->assertSame(null, $context->unk);
        $this->assertSame('first:one()', $context->one);
        $this->assertSame('ax', $context['a']);
        $this->assertSame(null, $context['unk']);
        $this->assertSame('first:one()', $context['one']);

        $this->assertTrue($context->methodExists('one'));
        $this->assertTrue($context->methodExists('three'));
        $this->assertFalse($context->methodExists('pone'));
        $this->assertFalse($context->methodExists('callMethod'));
        $this->assertFalse($context->methodExists('unk'));

        $this->assertSame('first:one(3,4)', $context->callMethod('one', array(3, 4), 'def'));
        $this->assertSame('def', $context->callMethod('pone', array(3, 4), 'def'));
        $this->assertSame('def', $context->callMethod('unk', array(3, 4), 'def'));
        $this->assertSame('first:one(4,5)', $context->one(4, 5));
        $this->assertSame(null, $context->pone(4, 5));
        $this->assertSame(null, $context->unk(4, 5));
    }

    /**
     * @dataProvider providerErrors
     * @param callable $func
     * @param string $exception
     */
    public function testErrors($func, $exception)
    {
        $this->setExpectedException($exception);
        \call_user_func($func);
    }

    /**
     * @return array
     */
    public function providerErrors()
    {
        return array(
            array(
                function () {
                    return new Context(1);
                },
                'InvalidArgumentException',
            ),
            array(
                function () {
                    return new Context(array(), 1);
                },
                'InvalidArgumentException',
            ),
            array(
                function () {
                    $context = new Context(array());
                    $context->x = 10;
                },
                'go\Structs\Exceptions\ReadOnlyFull',
            ),
            array(
                function () {
                    $context = new Context(array());
                    $context['x'] = 10;
                },
                'go\Structs\Exceptions\ReadOnlyFull',
            ),
            array(
                function () {
                    $context = new Context(array());
                    unset($context->x);
                },
                'go\Structs\Exceptions\ReadOnlyFull',
            ),
            array(
                function () {
                    $context = new Context(array());
                    unset($context['x']);
                },
                'go\Structs\Exceptions\ReadOnlyFull',
            ),
        );
    }
}
