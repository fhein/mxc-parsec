<?php

namespace Mxc\Parsec\Attribute;

use Mxc\Parsec\Qi\Unused;

class Optional
{
    protected $value;
    protected $set = false;

    public function __construct($value = null)
    {
        $this->set($value);
    }

    public function set($value)
    {
        $this->value = $value;
        $this->set = ! $value instanceof Unused;
    }

    public function get()
    {
        return $this->value;
    }
}
