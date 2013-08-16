<?php
/**
 * Test Simple-templater
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\Tpl;

use go\Structs\Tpl\Simple;

/**
 * @covers go\Structs\Tpl\Simple
 */
class SimpleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\Structs\Tpl\Simple::__construct
     * @covers go\Structs\Tpl\Simple::render
     */
    public function testRender()
    {
        $template = 'One: {{ one }}, Two: {{ two }}, Three: {{ three }}';
        $vars = array(
            'one' => '111',
            'three' => '333',
            'four' => '444',
        );
        $expected = 'One: 111, Two: , Three: 333';
        $tpl = new Simple($template);
        $this->assertEquals($expected, $tpl->render($vars));
    }
}
