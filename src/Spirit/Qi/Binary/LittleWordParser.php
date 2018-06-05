<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class LittleWordParser extends BinParser
{
    public function __construct(Domain $domain, string $uid, int $expectedValue = null)
    {
        $this->endianness = 'v';
        $this->size = 2;
        parent::__construct($domain, $uid, $expectedValue);
    }
}
