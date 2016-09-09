<?php

namespace Nox\Exceptions;

class MultiException extends \Exception implements \ArrayAccess, \Iterator, \Countable
{
    protected $data = [];

    public function current()
    {
        return current($this->data);
    }

    public function next()
    {
        next($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function valid()
    {
        return current($this->data) !== false;
    }

    public function rewind()
    {
        reset($this->data);
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if ($offset == '') {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function count()
    {
        return count($this->data);
    }
    
    public function getMessages()
    {
        $messages = array_map(function ($exception) {
            return $exception->getMessage();
        }, $this->data);
        
        return implode("\n", $messages);
    }
}
