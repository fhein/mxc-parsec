<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Exception\NotSupported32Exception;

class LittleQWordParser extends BinParser
{
    public function __construct(Domain $domain)
    {
        $this->endianness = 'P';
        $this->size = 8;
        if (PHP_INT_SIZE < 8) {
            throw new NotSupported32Exception(
                sprintf("%s not supported in 32-bit PHP builds.", $this->what())
            );
        }
        parent::__construct($domain);
    }
}
