<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\PredicateParser;

class NotPredicate extends PredicateParser
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return ! $this->subject->parseImpl($iterator, $skipper, null);
    }
}
