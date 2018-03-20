<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\HexIntPolicy;

class HexParser extends IntegerParser
{
    public function __construct(Domain $domain, int $minDigits = 1, int $maxDigits = -1)
    {
        parent::__construct($domain, new HexIntPolicy(), $minDigits, $maxDigits);
    }
}
