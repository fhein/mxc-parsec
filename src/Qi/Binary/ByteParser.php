<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class ByteParser extends BinParser
{
    public function __construct(Domain $domain)
    {
        $this->endianness = 'C';
        $this->size = 1;
        parent::__construct($domain);
    }
}
