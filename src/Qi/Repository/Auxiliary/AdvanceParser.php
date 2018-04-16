<?php

namespace Mxc\Parsec\Qi\Repository\Auxiliary;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\PrimitiveParser;

class AdvanceParser extends PrimitiveParser
{
    public function __construct(Domain $domain, int $advance)
    {
        $this->advance = $advance;
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        for ($i = 0; ($i < $this->advance) && $iterator->valid(); $i++) {
            $iterator->next();
        }
        return true;
    }
}
