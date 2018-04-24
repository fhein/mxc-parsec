<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\PredicateParser;

class NotPredicate extends PredicateParser
{
    public function doParse($skipper)
    {
        return ! $this->subject->parse($skipper);
    }
}
