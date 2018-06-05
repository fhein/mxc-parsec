<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Exception\NotSupported32Exception;

class QWordParser extends BinParser
{
    public function __construct(Domain $domain, string $uid, int $expectedValue = null)
    {
        if (PHP_INT_SIZE < 8) {
            throw new NotSupported32Exception(
                sprintf("%s not supported in 32-bit PHP builds.", $this->what())
            );
        }
        $this->endianness = 'Q';
        $this->size = 8;
        parent::__construct($domain, $uid, $expectedValue);
    }
}
