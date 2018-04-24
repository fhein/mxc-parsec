<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class BigDWordParser extends BinParser
{

    public function __construct(Domain $domain, int $expectedValue = null)
    {
        $this->endianness = 'N';
        $this->size = 4;
        parent::__construct($domain, $expectedValue);
    }
}
