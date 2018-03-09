<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\UnaryParser;

class KleeneOperator extends UnaryParser
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->attr = [];
        $subject = $this->subject;
        while ($subject->parseImpl($iterator, $expectedValue, $attributeType. $skipper)) {
            $this->assignTo($subject->getAttribute(), $attributeType);
        }
        return true;
    }
}
