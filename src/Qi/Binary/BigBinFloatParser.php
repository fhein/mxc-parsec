<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class BigBinFloatParser extends BinParser
{

    public function __construct(Domain $domain)
    {
        $this->endianness = 'G';
        $this->size = 4;
        parent::__construct($domain);
    }
}
