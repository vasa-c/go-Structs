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
     * @var array
     */
    private $args;
}
