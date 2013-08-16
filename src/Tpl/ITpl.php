<?php
/**
 * Interface of template renderer
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Tpl;

interface ITpl
{
    /**
     * Render vars in template
     *
     * @param array $vars [optional]
     * @return string
     * @throws \go\Structs\Tpl\TemplateError
     */
    public function render(array $vars = null);
}
