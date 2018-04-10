<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\UnaryParser;

class PlusOperator extends UnaryParser
{
    protected $defaultType = 'array';

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subject = $this->subject;
        if (! $subject->parse($iterator, $expectedValue, null, $skipper)) {
            return false;
        }
        $this->assignTo($subject->getAttribute, $attributeType);
        while ($subject->parse($iterator, $expectedValue, null, $skipper)) {
            $this->assignTo($subject->getAttribute(), $attributeType);
        }
        return true;
    }
}
