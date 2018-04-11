<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalUIntPolicy;

class UIntParser extends Integer
{
    public function __construct(Domain $domain, $minDigits = 1, int $maxDigits = -1)
    {
        parent::__construct($domain, new DecimalUIntPolicy(), $minDigits, $maxDigits);
    }
}
