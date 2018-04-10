<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\PredicateParser;

class AndPredicate extends PredicateParser
{
    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return $this->subject->parse($iterator, null, null, $skipper);
    }
}
