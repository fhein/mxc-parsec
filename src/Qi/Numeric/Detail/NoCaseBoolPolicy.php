<?php

namespace Mxc\Parsec\Qi\Numeric\Detail;

class NoCaseBoolPolicy extends BoolPolicy
{

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
}
