<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class DifferenceOperator extends NaryParser
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $lhs = $this->subject[0];
        $rhs = $this->subject[1];

        $count = count($this->subject);
        for ($idx = 1; $idx < $count; $idx++) {
            if ($this->subject[$idx]->parseImpl($iterator, $expectedValue, $attributeType. $skipper)) {
                return false;
            }
        }

        if (! $lhs->parseImpl($iterator, $expectedValue, $attributeType. $skipper)) {
            return false;
        }

        $this->assignTo($lhs->getAttribute(), $attributeType);
        return true;
    }
}
