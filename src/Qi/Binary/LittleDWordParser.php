<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;

class LittleDWordParser extends BinParser
{

    public function __construct(Domain $domain, int $value = null)
    {
        $this->endianness = 'V';
        $this->size = 4;
        parent::construct($domain, $value);
    }
}
