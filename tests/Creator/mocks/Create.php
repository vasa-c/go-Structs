<?php
/**
 * Mock for test Creator::create
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs\Creator\mocks;

class Create
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->args = \func_get_args();
        self::$calls[] = isset($this->args[0]) ? $this->args[0] : null;
    }

    /**
     * Get constructor arguments
     *
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @return array
     */
    public static function getCalls()
    {
        return self::$calls;
    }

    public static function resetCalls()
    {
        self::$calls = array();
    }

    /**
     * @var array
     */
    private $args;

    /**
     * @var array
     */
    private static $calls = array();
}
