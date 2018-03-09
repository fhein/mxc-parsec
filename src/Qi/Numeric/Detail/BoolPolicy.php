<?php

namespace Mxc\Parsec\Qi\Numeric\Detail;

class BoolPolicy
{
    public $symbols;

    public function __construct()
    {
        $this->symbols =
        [
            'true' => true,
            'false' => false,
        ];
    }
}
