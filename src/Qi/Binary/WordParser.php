<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class WordParser extends BinParser
{

    public function __construct(Domain $domain, int $value = null)
    {
        $this->endianness = 'S';
        $this->size = 2;
        parent::__construct($domain, $value);
    }
}
