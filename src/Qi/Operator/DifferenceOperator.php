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
        if ($rhs->parse($iterator, null, $attributeType, $skipper)) {
            $iterator->reject();
            return false;
        }
        $iterator->reject();
        if (! $lhs->parse($iterator, null, $attributeType, $skipper)) {
            return false;
        }

        $this->assignTo($lhs->getAttribute(), $attributeType);
        return true;
    }
}
