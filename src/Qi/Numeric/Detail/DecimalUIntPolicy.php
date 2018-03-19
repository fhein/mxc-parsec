<?php

namespace Mxc\Parsec\Qi\Numeric\Detail;

use Mxc\Parsec\Qi\Numeric\Detail\IntegerPolicy;

class DecimalUIntPolicy extends IntegerPolicy
{
    public function __construct()
    {
        parent::__construct(
            [
                '0' => 0,
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
                '6' => 6,
                '7' => 7,
                '8' => 8,
                '9' => 9
            ],
            [
                '+' => 1
            ]
        );
        $this->toString = 'strval';
        $this->toDecimal = 'intval';
    }
}
