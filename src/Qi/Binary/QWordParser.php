<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Exception\NotSupported32Exception;

class QWordParser extends BinParser
{

    public function __construct(Domain $domain, int $value = null)
    {
        $this->endianness = 'Q';
        $this->size = 8;
        if (PHP_INT_SIZE < 8) {
            throw new NotSupported32Exception(
                sprintf("%s not supported in 32-bit PHP builds.", $this->what())
            );
        }
        parent::__construct($domain, $value);
    }
}
