<?php
/**
 * Paths on server
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs;

/**
 * @property-read string $root
 */
class Paths
{
    /**
     * Constructor
     *
     * @param string $root
     * @param array $templates
     * @param string $sep [optional]
     */
    public function __construct($root, array $templates, $sep = null)
    {
        $this->paths = array(
            'root' => $root,
        );
        $this->proot = $root;
        $this->templates = $templates;
        $this->sep = $sep ?: \DIRECTORY_SEPARATOR;
    }

    /**
     * Magic get
     *
     * @param string $key
     * @return string
     * @throws \go\Structs\Exceptions\SubserviceNotFound
     */
    public function __get($key)
    {
        if (!isset($this->paths[$key])) {
            $this->paths[$key] = $this->create($key);
        }
        return $this->paths[$key];
    }

    /**
     * Magic isset
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return (isset($this->templates[$key]) || ($key === 'root'));
    }

    /**
     * Magic set (forbidden)
     *
     * @param string $key
     * @param mixed $value
     * @throws \go\Structs\Exceptions\ReadOnlyFull
     */
    public function __set($key, $value)
    {
        throw new Exceptions\ReadOnlyFull('Paths', $key);
    }

    /**
     * Magic unset (forbidden)
     *
     * @param string $key
     * @throws \go\Structs\Exceptions\ReadOnlyFull
     */
    public function __unset($key)
    {
        throw new Exceptions\ReadOnlyFull('Paths', $key);
    }

    /**
     * Magic toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->proot;
    }

    /**
     * @param string $key
     * @throws \go\Structs\Exceptions\SubserviceNotFound
     */
    protected function create($key)
    {
        if (!isset($this->templates[$key])) {
            throw new Exceptions\SubserviceNotFound('Paths', $key);
        }
        $template = $this->templates[$key];
        if (\is_array($template)) {
            $root = $this->createByTemplate($template['root']);
            return new self($root, $template['paths'], $this->sep);
        } else {
            return $this->createByTemplate($template);
        }
    }

    /**
     * @param string $template
     * @return string
     * @throws \go\Structs\Exceptions\SubserviceNotFound
     */
    protected function createByTemplate($template)
    {
        $first = \substr($template, 0, 1);
        if ($first === '/') {
            return $template;
        }
        if ($first === '{') {
            if (\preg_match('~^{{(.*?)}}(.*)?$~s', $template, $matches)) {
                $name = \trim($matches[1]);
                $suffix = $matches[2];
                $prefix = $this->__get($name);
                if (\is_object($prefix)) {
                    $prefix = $prefix->root;
                }
                return $prefix.$suffix;
            }
        }
        return $this->proot.$this->sep.$template;
    }

    /**
     * @var string
     */
    protected $proot;

    /**
     * @var array
     */
    protected $paths;

    /**
     * @var array
     */
    protected $templates;

    /**
     * @var string
     */
    protected $sep;
}
