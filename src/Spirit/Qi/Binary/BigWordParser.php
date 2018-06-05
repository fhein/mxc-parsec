<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class BigWordParser extends BinParser
{
    public function __construct(Domain $domain, string $uid, int $expectedValue = null)
    {
        $this->endianness = 'n';
        $this->size = 2;
        parent::__construct($domain, $uid, $expectedValue);
    }
}
