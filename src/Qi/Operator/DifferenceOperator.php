<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\BinaryParser;

class DifferenceOperator extends BinaryParser
{
    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $lhs = $this->subject[0];
        $rhs = $this->subject[1];

        $count = count($this->subject);
        for ($idx = 1; $idx < $count; $idx++) {
            if ($this->subject[$idx]->parse($iterator, null, null, $skipper)) {
                return false;
            }
        }

        if (! $lhs->parse($iterator, null, null, $skipper)) {
            return false;
        }

        $this->assignTo($lhs->getAttribute(), $attributeType);
        return true;
    }
}
