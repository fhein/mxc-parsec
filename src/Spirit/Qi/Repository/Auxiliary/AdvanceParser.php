<?php

namespace Mxc\Parsec\Qi\Repository\Auxiliary;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\PrimitiveParser;

class AdvanceParser extends PrimitiveParser
{
    public function __construct(Domain $domain, string $uid, int $advance)
    {
        parent::__construct($domain, $uid);
        $this->advance = $advance;
    }

    public function doParse($skipper)
    {
        for ($i = 0; $i < $this->advance && $this->iterator->valid(); $i++) {
            $this->iterator->current();
            $this->iterator->next();
        }
        return ($i === $this->advance);
    }
}
