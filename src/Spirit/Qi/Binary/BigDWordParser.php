<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class BigDWordParser extends BinParser
{

    public function __construct(Domain $domain, string $uid, int $expectedValue = null)
    {
        $this->endianness = 'N';
        $this->size = 4;
        parent::__construct($domain, $uid, $expectedValue);
    }
}
