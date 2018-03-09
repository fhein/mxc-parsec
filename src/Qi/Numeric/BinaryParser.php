<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\BinaryIntPolicy;

class BinaryParser extends IntParser
{

    public function __construct(Domain $domain, int $minDigits = 1, int $maxDigits = -1)
    {
        parent::construct($domain, new BinaryIntPolicy(), $minDigits, $maxDigits);
    }
}
