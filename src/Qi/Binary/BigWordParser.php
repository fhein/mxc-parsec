<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class BigWordParser extends BinParser
{

    public function __construct(Domain $domain, int $value = null)
    {
        $this->endianness = 'n';
        $this->size = 2;
        parent::__construct($domain, $value);
    }
}
