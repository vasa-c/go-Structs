<?php
/**
 * Templater: str_replace
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Tpl;

class Replace extends \go\Structs\Tpl\Base
{
    /**
     * @override \go\Structs\Tpl\Base
     *
     * @param array $vars
     * @return string
     */
    protected function irender(array $vars)
    {
        $open = $this->options['open'];
        $close = $this->options['close'];
        $search = array();
        $replace = array();
        foreach ($vars as $k => $v) {
            $search[] = $open.$k.$close;
            $replace[] = $v;
        }
        return \str_replace($search, $replace, $this->template);
    }

    /**
     * @override \go\Structs\Tpl\Base
     *
     * @var array
     */
    protected $defaultOptions = array(
        'open' => '{{ ',
        'close' => ' }}',
    );
}
