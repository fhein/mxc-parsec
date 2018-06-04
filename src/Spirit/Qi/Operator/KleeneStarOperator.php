<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\UnaryParser;

class KleeneStarOperator extends UnaryParser
{
    public function doParse($skipper)
    {
        $this->attribute = [];
        $subject = $this->getSubject();
        while ($subject->parse($skipper)) {
            $this->attribute[] = $subject->getAttribute();
        }
        return true;
    }
}
