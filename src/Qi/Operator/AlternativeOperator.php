<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\NaryParser;

class AlternativeOperator extends NaryParser
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        // return on first match
        $assignment = null;
        foreach ($this->subject as $parser) {
            if ($parser->parseImpl($iterator, $expectedValue, $attributeType, $skipper)) {
                if ($assignment === null) {
                    $assignment = $this->getAssignment($attributeType);
                }
                $this->assignTo($parser->getAttribute(), $attributeType);
                return true;
            }
        }
        return false;
    }
}
