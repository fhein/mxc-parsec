<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\BinaryParser;

class ListOperator extends BinaryParser
{
    protected $parsers;

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $lhs = $this->subject[0];

        if (! $lhs->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            return false;
        }
        $this->attribute[] = $lhs->getAttribute();
        $rhs = $this->subject[1];

        while (true) {
            $save = $iterator->key();
            if ($rhs->parse($iterator, $skipper, null)
                && $this->subject[1]->parse($iterator, $expectedValue, $attributeType, $skipper)) {
                    $this->assignTo($lhs->getAttribute(), $attributeType);
                    continue;
            }
            $iterator->setPos($save);
            break;
        }
        return true;
    }
}
