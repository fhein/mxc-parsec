<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class BigBinDoubleParser extends BinParser
{
    public function __construct(Domain $domain, $expectedValue = null)
    {
        $this->endianness = 'E';
        $this->size = 8;
        parent::__construct($domain, $expectedValue);
    }
}
