<?php
/**
 * Basic implementation of ITpl
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Tpl;

abstract class Base implements ITpl
{
    /**
     * Constructor
     *
     * @param string $template
     * @param array $options
     */
    public function __construct($template, array $options = null)
    {
        $this->template = $template;
        $this->options = $options ?: array();
        $this->normalizeOptions();
    }

    /**
     * @override \go\Structs\Tpl\ITpl
     *
     * @param array $vars [optional]
     * @return string
     * @throws \go\Structs\Tpl\TemplateError
     */
    public function render(array $vars = null)
    {
        if (!$this->compiled) {
            $this->compile();
            $this->compiled = true;
        }
        return $this->irender($vars ?: array());
    }

    /**
     * Magic invoke
     *
     * @param array $vars [optional]
     * @return string
     * @throws \go\Structs\Tpl\TemplateError*
     */
    public function __invoke(array $vars = null)
    {
        return $this->render($vars);
    }

    /**
     * Compile template (for override)
     *
     * @throws \go\Structs\Tpl\TemplateError
     */
    protected function compile()
    {
    }

    /**
     * Normalize options structure (for override)
     */
    protected function normalizeOptions()
    {
        if (\is_array($this->defaultOptions)) {
            $this->options = \array_merge($this->defaultOptions, $this->options);
        }
    }

    /**
     * Implementation of render
     *
     * @param array $vars
     * @return string
     */
    abstract protected function irender(array $vars);

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $options;

    /**
     * Default options structure
     *
     * @var array
     */
    protected $defaultOptions;

    /**
     * @var boolean
     */
    protected $compiled = false;
}
