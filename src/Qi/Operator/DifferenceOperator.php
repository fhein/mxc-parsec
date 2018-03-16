<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class DifferenceOperator extends NaryParser
{
    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $lhs = $this->subject[0];
        $rhs = $this->subject[1];

        $count = count($this->subject);
        for ($idx = 1; $idx < $count; $idx++) {
            if ($this->subject[$idx]->parse($iterator, $expectedValue, $attributeType, $skipper)) {
                return false;
            }
        }

        if (! $lhs->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            return false;
        }

        $this->assignTo($lhs->getAttribute(), $attributeType);
        return true;
    }
}
