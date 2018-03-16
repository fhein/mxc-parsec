<?php

namespace Mxc\Parsec\Qi;

class PredicateParser extends UnaryParser
{
    public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        // predicate parser do not consume any input
        $iterator->try();
        $result = $this->doParse($iterator, $expectedValue, $attributeType, $skipper);
        // restore position
        $iterator->reject();
        return $result;
    }
}
