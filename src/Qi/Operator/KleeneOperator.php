<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\UnaryParser;

class KleeneOperator extends UnaryParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->attr = [];
        $subject = $this->subject;
        while ($subject->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            $this->assignTo($subject->getAttribute(), $attributeType);
        }
        return true;
    }
}
