<?php

namespace Mxc\Parsec\Qi\Numeric\Detail;

class BinIntPolicy extends IntegerPolicy
{
    public function __construct()
    {
        parent::__construct(
            [
                '0' => 0,
                '1' => 1,
            ],
            [
                // no signs
            ]
        );
        $this->toString = 'decbin';
        $this->toDecimal = 'bindec';
    }
}
