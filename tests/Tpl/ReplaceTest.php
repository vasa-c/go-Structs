<?php
/**
 * Test Replace-templater
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\Tpl;

use go\Structs\Tpl\Replace;

/**
 * @covers go\Structs\Tpl\Replace
 */
class ReplaceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers go\Structs\Tpl\Replace::render
     * @dataProvider providerRender
     * @param string $template
     * @param array $vars
     * @param string $expected
     */
    public function testRender($template, $vars, $expected)
    {
        $tpl = new Replace($template);
        $this->assertEquals($expected, $tpl->render($vars));
    }

    /**
     * @return array
     */
    public function providerRender()
    {
        $template = 'Name is {{ name }}, age is {{ age }}.';
        return array(
            array(
                $template,
                null,
                'Name is {{ name }}, age is {{ age }}.'
            ),
            array(
                $template,
                array(),
                'Name is {{ name }}, age is {{ age }}.'
            ),
            array(
                $template,
                array('name' => 'Vasa', 'unk' => 'Unk'),
                'Name is Vasa, age is {{ age }}.'
            ),
            array(
                $template,
                array('name' => 'Vasa', 'unk' => 'Unk', 'age' => 10),
                'Name is Vasa, age is 10.'
            ),
        );
    }

    /**
     * @covers go\Structs\Tpl\Replace::__construct
     * @covers go\Structs\Tpl\Replace::render
     */
    public function testOptions()
    {
        $template = 'Name is {{ name }}, age is <!-- age -->.';
        $options = array(
            'open' => '<!-- ',
            'close' => ' -->',
        );
        $tpl = new Replace($template, $options);
        $vars = array(
            'name' => 'Vasa',
            'age' => 10,
        );
        $expected = 'Name is {{ name }}, age is 10.';
        $this->assertEquals($expected, $tpl->render($vars));
    }

    /**
     * @covers go\Structs\Tpl\Replace::__invoke
     */
    public function testInvoke()
    {
        $template = 'Name is {{ name }}, age is {{ age }}.';
        $tpl = new Replace($template);
        $vars = array(
            'name' => 'Petya',
            'age' => 12,
        );
        $expected = 'Name is Petya, age is 12.';
        $this->assertEquals($expected, $tpl($vars));
    }
}
