<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class LittleWordParser extends BinParser
{
    public function __construct(Domain $domain)
    {
        $this->endianness = 'v';
        $this->size = 2;
        parent::__construct($domain);
    }
}
