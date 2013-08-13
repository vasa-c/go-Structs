<?php
/**
 * Initialization of unit tests for go\Structs packages
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs;

use go\Structs\Autoloader;

require_once(__DIR__.'/../src/Autoloader.php');

Autoloader::register();
Autoloader::registerForTests(__DIR__);
