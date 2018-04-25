<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class DWordParser extends BinParser
{
    public function __construct(Domain $domain, int $expectedValue = null)
    {
        $this->endianness = 'L';
        $this->size = 4;
        parent::__construct($domain, $expectedValue);
    }
}
