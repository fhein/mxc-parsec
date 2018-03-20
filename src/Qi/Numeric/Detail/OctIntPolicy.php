<?php

namespace Mxc\Parsec\Qi\Numeric\Detail;

class OctIntPolicy extends IntegerPolicy
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
            ],
            [
                // no signs
            ]
        );
        $this->toDecimal = 'octdec';
    }
}
