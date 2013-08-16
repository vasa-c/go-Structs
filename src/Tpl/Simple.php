<?php
/**
 * Simple templater
 *
 * @package go\Request
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Tpl;

use go\Structs\Tpl\Helpers\Tokenizer;

class Simple extends Base
{
    /**
     * @override \go\Structs\Tpl\Base
     *
     * @param array $vars
     * @return string
     */
    protected function irender(array $vars)
    {
        $result = array();
        foreach ($this->tokens as $token) {
            switch ($token[0]) {
                case 'text':
                    $result[] = $token[1];
                    break;
                case 'tag':
                    $name = $token[1];
                    if (isset($vars[$name])) {
                        $result[] = $vars[$name];
                    }
                    break;
            }
        }
        return \implode('', $result);
    }

    /**
     * @override \go\Structs\Tpl\Base
     *
     * @throws \go\Structs\Tpl\TemplateError
     */
    protected function compile()
    {
        $o = $this->options;
        $this->tokens = Tokenizer::parseTags($this->template, $o['open'], $o['close']);
    }

    /**
     * @override \go\Structs\Tpl\Base
     *
     * @var array
     */
    protected $defaultOptions = array(
        'open' => '{{',
        'close' => '}}',
    );

    /**
     * Complied tokens
     *
     * @var array
     */
    protected $tokens;
}
