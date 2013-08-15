<?php
/**
 * Test ErrorsList
 *
 * @package go\Structs
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\Structs;

use go\Structs\ErrorsList;

/**
 * @covers go\Structs\ErrorsList
 */
class ErrorsListTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $errors = new ErrorsList();
        $this->assertTrue($errors->isSuccess());
        $this->assertFalse($errors->isFail());
        $this->assertCount(0, $errors);
        $this->assertEquals(array(), $errors->getListErrors());
    }

    public function testFail()
    {
        $errors = new ErrorsList();
        $errors->append('one error');
        $errors[] = 'two error';
        $errors[] = 'three error';
        $errors->append('four error');
        $this->assertFalse($errors->isSuccess());
        $this->assertTrue($errors->isFail());
        $this->assertCount(4, $errors);
        $expected = array(
            'one error',
            'two error',
            'three error',
            'four error',
        );
        $this->assertEquals($expected, $errors->getListErrors());
        $actual = array();
        foreach ($errors as $k => $v) {
            $actual[$k] = $v;
        }
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider providerMeaningless
     * @expectedException \go\Structs\Exceptions\Meaningless
     * @param callable $callback
     */
    public function testMeaningless($callback)
    {
        $errors = new ErrorsList();
        \call_user_func($callback, $errors);
    }

    /**
     * @return array
     */
    public function providerMeaningless()
    {
        return array(
            array(
                function ($errors) {
                    $errors[0] = 'error message';
                }
            ),
            array(
                function ($errors) {
                    return isset($errors[1]);
                }
            ),
            array(
                function ($errors) {
                    unset($errors[2]);
                }
            ),
            array(
                function ($errors) {
                    return $errors[3];
                }
            ),
        );
    }
}
