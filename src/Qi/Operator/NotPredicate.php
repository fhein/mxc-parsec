<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\PredicateParser;

class NotPredicate extends PredicateParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return ! $this->subject->parse($iterator, null, null, $skipper);
    }
}
