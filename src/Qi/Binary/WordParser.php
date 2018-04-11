<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class WordParser extends BinParser
{
    public function __construct(Domain $domain)
    {
        $this->endianness = 'S';
        $this->size = 2;
        parent::__construct($domain);
    }
}
