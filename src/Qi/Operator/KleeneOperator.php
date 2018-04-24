<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\UnaryParser;

class KleeneOperator extends UnaryParser
{
    public function doParse($skipper)
    {
        $this->attribute = [];
        $subject = $this->subject;
        while ($subject->parse($skipper)) {
            $this->attribute[] = $subject->getAttribute();
        }
        return true;
    }
}
