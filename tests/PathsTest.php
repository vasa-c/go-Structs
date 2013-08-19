<?php
/**
 * Test Paths class
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs;

use go\Structs\Paths;

/**
 * @covers go\Structs\Paths
 */
class PathsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\Structs\Paths::__construct
     * @covers go\Structs\Paths::__get
     * @covers go\Structs\Paths::__isset
     */
    public function testPaths()
    {
        $root = '/var/www';
        $templates = array(
            'etc' => 'etc',
            'tmp' => '/tmp',
            'libs' => 'framework/libs',
            'mylib' => '{{ libs }}/mylib/Autoloader.php',
            'module' => array(
                'root' => '{{ libs }}/module',
                'paths' => array(
                    'source' => 'src',
                    'tests' => 'tests',
                    'tmp' => '/tmp',
                ),
            ),
            'lmodule' => '{{ module }}/x',
        );
        $paths = new Paths($root, $templates, '/');

        $this->assertEquals('/var/www', $paths->root);
        $this->assertEquals('/var/www/etc', $paths->etc);
        $this->assertEquals('/tmp', $paths->tmp);
        $this->assertEquals('/var/www/framework/libs', $paths->libs);
        $this->assertEquals('/var/www/framework/libs/mylib/Autoloader.php', $paths->mylib);

        $module = $paths->module;
        $this->assertInstanceOf('go\Structs\Paths', $module);
        $this->assertEquals('/var/www/framework/libs/module', $module->root);
        $this->assertEquals('/var/www/framework/libs/module/src', $module->source);
        $this->assertEquals('/var/www/framework/libs/module/tests', $paths->module->tests);
        $this->assertEquals('/tmp', $module->tmp);
        $this->assertTrue(isset($paths->module->source));
        $this->assertFalse(isset($paths->module->etc));
        $this->assertSame($module, $paths->module);

        $this->assertEquals('/var/www/framework/libs/module/x', $paths->lmodule);
        $this->assertTrue(isset($paths->root));
        $this->assertTrue(isset($paths->tmp));
        $this->assertFalse(isset($paths->unk));
        $this->setExpectedException('go\Structs\Exceptions\NotFound');
        return $paths->unk;
    }

    /**
     * @covers go\Structs\Paths::__set
     * @expectedException go\Structs\Exceptions\ReadOnlyFull
     */
    public function testSet()
    {
        $paths = new Paths('/var/www', array());
        $paths->path = 'path';
    }

    /**
     * @covers go\Structs\Paths::__set
     * @expectedException go\Structs\Exceptions\ReadOnlyFull
     */
    public function testUnet()
    {
        $paths = new Paths('/var/www', array());
        unset($paths->path);
    }
}
