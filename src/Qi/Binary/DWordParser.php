<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class DWordParser extends BinParser
{

    public function __construct(Domain $domain)
    {
        $this->endianness = 'L';
        $this->size = 4;
        parent::__construct($domain);
    }
}
