<?php
/**
 * List of errors
 *
 * @package go\Structs
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Structs;

use go\Structs\Exceptions\Meaningless;

class ErrorsList implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * Append error
     *
     * @param string $message
     */
    public function append($message)
    {
        $this->errors[] = $message;
    }

    /**
     * Is success?
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return empty($this->errors);
    }

    /**
     * Is fail?
     *
     * @return boolean
     */
    public function isFail()
    {
        return (!empty($this->errors));
    }

    /**
     * @override \Countable
     *
     * @return int
     */
    public function count()
    {
        return \count($this->errors);
    }

    /**
     * Get list errors
     *
     * @return array
     */
    public function getListErrors()
    {
        return $this->errors;
    }

    /**
     * @override \IteratorAggregate
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->errors);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if ($offset !== null) {
            throw new Meaningless('Index of error in ErrorsList is meaningless');
        }
        $this->errors[] = $value;
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        throw new Meaningless('Read error by index from ErrorsList is meaningless');
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        throw new Meaningless('Checking error by index in ErrorsList is meaningless');
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        throw new Meaningless('Remove error from ErrorsList is meaningless');
    }

    /**
     * @var array
     */
    protected $errors = array();
}
