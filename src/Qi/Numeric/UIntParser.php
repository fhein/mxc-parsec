<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalUIntPolicy;

class UIntParser extends Integer
{
    public function __construct(Domain $domain, int $expectedValue = null, $minDigits = 1, int $maxDigits = -1)
    {
        parent::__construct($domain, new DecimalUIntPolicy(), $expectedValue, $minDigits, $maxDigits);
    }
}
