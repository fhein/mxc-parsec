<?php

namespace Mxc\Parsec\Support;

class NamedObject
{
    protected $name;

    public function __construct(string $name = null)
    {
        $this->name = $name ?? 'unnamed';
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __debugInfo()
    {
        return [
            'name'  => $this->name,
        ];
    }
}
