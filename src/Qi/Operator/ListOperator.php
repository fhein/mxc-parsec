<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class ListOperator extends NaryParser
{
    protected $parsers;

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $lhs = $this->subject[0];
        $rhs = $this->subject[1];

        if (! $lhs->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            return false;
        }
        $this->attribute[] = $lhs->getAttribute();

        while (true) {
            $save = $iterator->key();
            if ($rhs->parse($iterator, $skipper, null)
                && $lhs->parse($iterator, $expectedValue, $attributeType, $skipper)) {
                    $this->assignTo($lhs->getAttribute(), $attributeType);
                    continue;
            }
            $iterator->setPos($save);
        }
        return true;
    }
}
