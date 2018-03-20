<?php

namespace Mxc\Test\Parsec\Qi\Numeric\Assets;

use Mxc\Parsec\Qi\Numeric\Detail\BoolPolicy;

class BackwardsBoolPolicy extends BoolPolicy
{
    public function __construct()
    {
        $this->symbols =
        [
            'true' => true,
            'TRUE' => true,
            'eurt' => false,
            'EURT' => false,
        ];
    }
}
