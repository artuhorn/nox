<?php

namespace Nox\Core;

trait THash
{
    public function fromArray(array $data)
    {
        foreach ($data as $key => $element) {
            if (is_array($element)) {
                $this->{$key} = (new \ReflectionClass(Hash::class))->newInstanceWithoutConstructor();
                $this->{$key}->fromArray($element);
            } else {
                $this->{$key} = $element;
            }
        }
    }

    public function hashSet($name, $value)
    {
        if (is_array($value)) {
            $this->{$name}->fromArray($value);
        } elseif ($name == '') {
            $this->data[] = $value;
        } elseif (is_scalar($value)) {
            $this->data[$name] = $value;
        } else {
            $this->data[$name] = (new \ReflectionClass(Hash::class))->newInstanceWithoutConstructor();
        }
    }

    public function hashGet($name)
    {
        if (!isset($this->data[$name])) {
            $this->hashSet($name, null);
        }
        return $this->data[$name];
    }

    public function hashIsSet($name)
    {
        return isset($this->data[$name]);
    }

    public function hashUnset($name)
    {
        unset($this->data[$name]);
    }
}