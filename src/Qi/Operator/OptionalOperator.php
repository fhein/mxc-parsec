<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\UnaryParser;

class OptionalOperator extends UnaryParser
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if ($this->subject->parseImpl($iterator, $expectedValue, $attributeType. $skipper)) {
            $this->assignTo($this->subject->getAttribute(), $attributeType);
        }
        return true;
    }
}
