<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\NaryParser;

class AlternativeOperator extends NaryParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        // return on first match
        $assignment = null;
        foreach ($this->subject as $parser) {
            if ($parser->parse($iterator, $expectedValue, $attributeType, $skipper)) {
                $this->assignTo($parser->getAttribute(), $attributeType);
                return true;
            }
        }
        return false;
    }
}
