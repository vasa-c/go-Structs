<?php
/**
 * Tokenizer for templater
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Tpl\Helpers;

class Tokenizer
{
    /**
     * Get tokens from template
     *
     * @param string $template
     * @param string $open
     * @param string $close
     */
    public static function parseTags($template, $open, $close)
    {
        $result = array();
        $t = \explode($open, $template);
        $text = \array_shift($t);
        if (!empty($text)) {
            $result[] = array('text', $text);
        }
        foreach ($t as $item) {
            $item = \explode($close, $item, 2);
            $result[] = array('tag', \trim($item[0]));
            if (!empty($item[1])) {
                $result[] = array('text', $item[1]);
            }
        }
        return $result;
    }
}
