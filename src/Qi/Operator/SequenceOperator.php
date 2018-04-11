<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class SequenceOperator extends NaryParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $result = true;
        $i = 0;
        foreach ($this->subject as $parser) {
            $result = $result && $parser->parse($iterator, null, null, $skipper);
            if ($result === false) {
                return false;
            }
            $this->assignTo($parser->getAttribute(), $attributeType);
        }
        return true;
    }
}
