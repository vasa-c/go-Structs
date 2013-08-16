<?php
/**
 * Test Replace-templater
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\Tpl\Helpers;

use go\Structs\Tpl\Helpers\Tokenizer;

/**
 * @covers go\Structs\Tpl\Helpers\Tokenizer
 */
class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\Structs\Tpl\Helpers\Tokenizer::parseTags
     */
    public function testParseTags()
    {
        $template = 'One: {{ one }}, Two: {{ two }}{{ three }}';
        $tokens = array(
            array('text', 'One: '),
            array('tag', 'one'),
            array('text', ', Two: '),
            array('tag', 'two'),
            array('tag', 'three'),
        );
        $this->assertEquals($tokens, Tokenizer::parseTags($template, '{{', '}}'));
    }
}
