<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\HexIntPolicy;

class HexParser extends Integer
{
    public function __construct(
        Domain $domain,
        int $expectedValue = null,
        int $minDigits = 1,
        int $maxDigits = 0,
        int $minValue = null,
        int $maxValue = null
    ) {
        parent::__construct($domain, new HexIntPolicy(), $expectedValue, $minDigits, $maxDigits, $minValue, $maxValue);
    }
}
