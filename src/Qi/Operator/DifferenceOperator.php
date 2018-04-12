<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\BinaryParser;

class DifferenceOperator extends BinaryParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $lhs = $this->subject[0];
        $rhs = $this->subject[1];

        $iterator->try();
        if ($rhs->parse($iterator, null, null, $skipper)) {
            $iterator->reject();
            return false;
        }
        $iterator->reject();
        if (! $lhs->parse($iterator, null, null, $skipper)) {
            return false;
        }

        return $this->validate($expectedValue, $lhs->getAttribute(), $attributeType);
    }
}
