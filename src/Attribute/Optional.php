<?php

namespace Mxc\Parsec\Attribute;

class Optional
{
    protected $value;

    public function __construct($value = null)
    {
        $this->set($value);
    }

    public function set($value)
    {
        $this->value = $value;
    }

    public function get()
    {
        return $this->value;
    }
}
