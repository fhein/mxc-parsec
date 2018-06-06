<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\BinaryParser;

class DifferenceOperator extends BinaryParser
{
    public function doParse($skipper)
    {
        $lhs = $this->getSubject(0);
        $rhs = $this->getSubject(1);

        $this->try();
        if ($rhs->parse($skipper)) {
            $this->reject();
            return false;
        }
        $this->reject();
        if (! $lhs->parse($skipper)) {
            return false;
        }

        $this->attribute = $lhs->getAttribute();
        return true;
    }
}
