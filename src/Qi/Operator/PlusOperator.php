<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\UnaryParser;

class PlusOperator extends UnaryParser
{
    public function doParse($skipper)
    {
        $subject = $this->subject;
        if (! $subject->parse($skipper)) {
            return false;
        }
        $this->attribute[] = $subject->getAttribute();
        while ($subject->parse($skipper)) {
            $this->attribute[] = $subject->getAttribute();
        }
        return true;
    }
}
