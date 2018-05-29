<?php

namespace Mxc\Parsec\Qi\Numeric\Detail;

class BoolPolicy
{
    protected $symbols;

    public function __construct()
    {
        $this->symbols =
        [
            'true' => true,
            'false' => false,
        ];
    }

    public function getSymbols()
    {
        return $this->symbols;
    }
}
