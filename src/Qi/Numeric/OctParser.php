<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\OctalIntPolicy;

class OctParser extends IntParser
{

    public function __construct(Domain $domain, int $minDigits = 1, int $maxDigits = -1)
    {
        parent::construct($domain, new OctalIntPolicy(), $minDigits, $maxDigits);
    }
}
