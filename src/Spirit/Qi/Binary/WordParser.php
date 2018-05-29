<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;

class WordParser extends BinParser
{
    public function __construct(Domain $domain, int $expectedValue = null)
    {
        $this->endianness = 'S';
        $this->size = 2;
        parent::__construct($domain, $expectedValue);
    }
}
