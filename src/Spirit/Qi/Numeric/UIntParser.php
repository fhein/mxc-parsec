<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalUIntPolicy;

class UIntParser extends Integer
{
    public function __construct(
        Domain $domain,
        string $uid,
        int $expectedValue = null,
        int $minDigits = 1,
        int $maxDigits = 0,
        int $minValue = null,
        int $maxValue = null
    ) {
        parent::__construct(
            $domain,
            $uid,
            new DecimalUIntPolicy(),
            $expectedValue,
            $minDigits,
            $maxDigits,
            $minValue,
            $maxValue
        );
    }
}
