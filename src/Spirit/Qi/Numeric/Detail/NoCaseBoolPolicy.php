<?php

namespace Mxc\Parsec\Qi\Numeric\Detail;

class NoCaseBoolPolicy extends BoolPolicy
{
    protected $symbols;

    public function __construct()
    {
        $this->symbols =
        [
            'true' => true,
            'TRUE' => true,
            'false' => false,
            'FALSE' => false,
        ];
    }

    public function getSymbols()
    {
        return $this->symbols;
    }
}
