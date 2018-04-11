<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class LittleDWordParser extends BinParser
{
    public function __construct(Domain $domain)
    {
        $this->endianness = 'V';
        $this->size = 4;
        parent::__construct($domain);
    }
}
