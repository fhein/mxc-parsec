<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\BinIntPolicy;

class BinaryParser extends Integer
{
    public function __construct(Domain $domain, int $expectedValue = null, int $minDigits = 1, int $maxDigits = -1)
    {
        parent::__construct($domain, new BinIntPolicy(), $expectedValue, $minDigits, $maxDigits);
    }
}
