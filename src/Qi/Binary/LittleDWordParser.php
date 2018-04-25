<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class LittleDWordParser extends BinParser
{
    public function __construct(Domain $domain, int $expectedValue = null)
    {
        $this->endianness = 'V';
        $this->size = 4;
        parent::__construct($domain, $expectedValue);
    }
}
