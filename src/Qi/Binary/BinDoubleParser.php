<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class BinDoubleParser extends BinParser
{

    public function __construct(Domain $domain, int $value = null)
    {
        $this->endianness = 'd';
        $this->size = 8;
        parent::__construct($domain, $value);
    }
}
