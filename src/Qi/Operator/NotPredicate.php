<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\PredicateParser;

class NotPredicate extends PredicateParser
{

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return ! $this->subject->parse($iterator, $skipper, null);
    }
}