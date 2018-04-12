<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\UnaryParser;

class PlusOperator extends UnaryParser
{
    protected $defaultType = 'array';

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subject = $this->subject;
        if (! $subject->parse($iterator, null, null, $skipper)) {
            return false;
        }
        $this->assignTo($subject->getAttribute(), $attributeType);
        while ($subject->parse($iterator, $expectedValue, null, $skipper)) {
            $this->assignTo($subject->getAttribute(), $attributeType);
        }
        return true;
    }
}
