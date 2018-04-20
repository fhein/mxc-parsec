<?php

namespace Mxc\Parsec\Qi\Repository\Auxiliary;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\PrimitiveParser;

class AdvanceParser extends PrimitiveParser
{
    public function __construct(Domain $domain, int $advance)
    {
        parent::__construct($domain);
        $this->advance = $advance;
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        for ($i = 0; $i < $this->advance && $iterator->valid(); $i++) {
            $iterator->current();
            $iterator->next();
        }
        return ($i === $this->advance);
    }
}
