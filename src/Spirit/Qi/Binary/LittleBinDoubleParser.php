<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class LittleBinDoubleParser extends BinParser
{
    public function __construct(Domain $domain, string $uid, float $expectedValue = null)
    {
        $this->endianness = 'e';
        $this->size = 8;
        parent::__construct($domain, $uid, $expectedValue);
    }
}
