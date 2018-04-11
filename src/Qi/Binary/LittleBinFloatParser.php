<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class LittleBinFloatParser extends BinParser
{
    public function __construct(Domain $domain)
    {
        $this->endianness = 'g';
        $this->size = 4;
        parent::__construct($domain);
    }
}
