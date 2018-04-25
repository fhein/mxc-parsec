<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class BigBinFloatParser extends BinParser
{
    public function __construct(Domain $domain, float $expectedValue = null)
    {
        $this->endianness = 'G';
        $this->size = 4;
        parent::__construct($domain, $expectedValue);
    }
}
