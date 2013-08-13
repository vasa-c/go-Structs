<?php
/**
 * Data structures
 *
 * @package go\Structs
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 * @uses PHP 5.3+
 */

namespace go\Structs;

const VERSION = '0';

/**
 * Autoloader for go\Structs classes
 */
final class Autoloader
{
    /**
     * Register autoloader for this lib
     */
    public static function register()
    {
        if (!self::$autoloader) {
            self::$autoloader = new self(__NAMESPACE__, __DIR__);
            \spl_autoload_register(self::$autoloader);
        }
    }

    /**
     * Register autoloader for unit tests this lib
     *
     * @param string $dir [optional]
     *        root dir of unit tests
     */
    public static function registerForTests($dir = null)
    {
        if (!self::$autoloaderForTests) {
            $dir = $dir ?: \realpath(__DIR__.'/../tests');
            self::$autoloaderForTests = new self('go\Tests\Structs', $dir);
            \spl_autoload_register(self::$autoloaderForTests);
        }
    }

    /**
     * Constructor
     *
     * @param string $namespace
     * @param string $dir
     */
    private function __construct($namespace, $dir)
    {
        $this->namespace = $namespace;
        $this->dir = $dir;
    }

    /**
     * Invoke - load class by name
     *
     * @param string $classname
     */
    public function __invoke($classname)
    {
        $prefix = $this->namespace.'\\';
        if (\strpos($classname, $prefix) !== 0) {
            return;
        }
        $short = \substr($classname, \strlen($prefix));
        $filename = \str_replace('\\', \DIRECTORY_SEPARATOR, $short);
        $filename = $this->dir.\DIRECTORY_SEPARATOR.$filename.'.php';
        if (\is_file($filename)) {
            require_once($filename);
        }
    }

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $dir;

    /**
     * @var callable
     */
    private static $autoloader;

    /**
     * @var callable
     */
    private static $autoloaderForTests;
}
