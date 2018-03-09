<?php

namespace Mxc\Parsec\Qi\Numeric\Detail;

class HexIntPolicy extends IntegerPolicy
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
                '9' => 9,
                'a' => 10,
                'b' => 11,
                'c' => 12,
                'd' => 13,
                'e' => 14,
                'f' => 15,
                'A' => 10,
                'B' => 11,
                'C' => 12,
                'D' => 13,
                'E' => 14,
                'F' => 15,
            ],
            [
            // no signs
            ]
        );
            // override wrong radix
            $this->radix = 16;
            $this->toString = 'dechex';
            $this->toDecimal = 'hexdec';
    }
}
