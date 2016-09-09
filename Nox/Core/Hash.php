<?php

namespace Nox\Core;

class Hash implements \ArrayAccess, \Countable, \IteratorAggregate
{
    // Implementation ArrayAccess interface
    use TArrayAccess;
    use THash;

    protected $data = [];

    public function __set($name, $value)
    {
        $this->hashSet($name, $value);
    }

    public function __get($name)
    {
        return $this->hashGet($name);
    }

    public function __isset($name)
    {
        return $this->hashIsSet($name);
    }

    public function __unset($name)
    {
        $this->hashUnset($name);
    }

    // Implementation Countable interface
    public function count()
    {
        return count($this->data);
    }

    // Implementation IteratorAggregate interface
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
