<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class LittleBinFloatParser extends BinParser
{
    public function __construct(Domain $domain, $expectedValue = null)
    {
        $this->endianness = 'g';
        $this->size = 4;
        parent::__construct($domain, $expectedValue);
    }
}
