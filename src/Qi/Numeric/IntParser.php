<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalIntPolicy;

class IntParser extends Integer
{
    public function __construct(Domain $domain, int $minDigits = 1, int $maxDigits = -1)
    {
        parent::__construct($domain, new DecimalIntPolicy(), $minDigits, $maxDigits);
    }
}
