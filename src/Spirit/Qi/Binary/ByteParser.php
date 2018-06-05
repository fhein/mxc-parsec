<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class ByteParser extends BinParser
{
    public function __construct(Domain $domain, string $uid, int $expectedValue = null)
    {
        $this->endianness = 'C';
        $this->size = 1;
        parent::__construct($domain, $uid, $expectedValue);
    }
}
