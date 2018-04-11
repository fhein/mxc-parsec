<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class BinDoubleParser extends BinParser
{
    public function __construct(Domain $domain)
    {
        $this->endianness = 'd';
        $this->size = 8;
        parent::__construct($domain);
    }
}
