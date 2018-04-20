<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class LittleBinDoubleParser extends BinParser
{
    public function __construct(Domain $domain)
    {
        $this->endianness = 'e';
        $this->size = 8;
        parent::__construct($domain);
    }
}
