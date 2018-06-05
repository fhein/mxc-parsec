<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalIntPolicy;

class IntParser extends Integer
{
    public function __construct(
        string $uid,
        Domain $domain,
        int $expectedValue = null,
        int $minDigits = 1,
        int $maxDigits = 0,
        int $minValue = null,
        int $maxValue = null
    ) {
        parent::__construct(
            $uid,
            $domain,
            new DecimalIntPolicy(),
            $expectedValue,
            $minDigits,
            $maxDigits,
            $minValue,
            $maxValue
        );
    }
}
