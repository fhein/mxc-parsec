<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\UnaryParser;

class PlusOperator extends UnaryParser
{

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subject = $this->subject;
        if (! $subject->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            return false;
        }
        $assignment = null;
        while ($subject->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            if ($assignment === null) {
                $assignment = $this->getAssignment($attributeType);
            }
            call_user_func($assignment, $subject->getAttribute());
        }
        return true;
    }
}
