<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\UnaryParser;
use Mxc\Parsec\Attribute\Optional;
use Mxc\Parsec\Attribute\Unused;

class OptionalOperator extends UnaryParser
{
    public function doParse($skipper)
    {
        $subject = $this->getSubject();
        if ($subject->parse($skipper)) {
            $this->attribute = new Optional($subject->getAttribute());
        } else {
            $this->attribute = new Optional(new Unused());
        }
        return true;
    }
}
