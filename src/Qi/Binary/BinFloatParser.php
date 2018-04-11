<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class BinFloatParser extends BinParser
{
    public function __construct(Domain $domain)
    {
        $this->endianness = 'f';
        $this->size = 4;
        parent::__construct($domain);
    }
}
