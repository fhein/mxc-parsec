<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\BinaryParser;

class DifferenceOperator extends BinaryParser
{
    public function doParse($skipper)
    {
        $lhs = $this->getSubject(0);
        $rhs = $this->getSubject(1);

        $this->iterator->try();
        if ($rhs->parse($skipper)) {
            $this->iterator->reject();
            return false;
        }
        $this->iterator->reject();
        if (! $lhs->parse($skipper)) {
            return false;
        }

        $this->attribute = $lhs->getAttribute();
        return true;
    }
}
