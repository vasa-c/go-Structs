<?php
/**
 * Error config format
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs\Exceptions;

class ConfigFormat extends Logic
{
    /**
     * Constructor
     *
     * @param string $owner
     * @param string $desc
     */
    public function __construct($owner = null, $desc = null)
    {
        $this->owner = $owner;
        $this->desc = $desc;
        $message = 'Error config'.($owner ? ' for '.$owner : '').($desc ? ': '.$desc : '');
        parent::__construct($message);
    }

    /**
     * @return string
     */
    final public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    final public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @var string
     */
    private $owner;

    /**
     * @var string
     */
    private $desc;
}
