<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\PredicateParser;

class AndPredicate extends PredicateParser
{
    public function doParse($skipper)
    {
        return $this->getSubject()->parse($skipper);
    }
}
