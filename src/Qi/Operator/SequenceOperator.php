<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class SequenceOperator extends NaryParser
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subtype = $this->isScalar($attributeType) ? 'string' : $attributeType;
        // @todo: Not quite sure about this one
        if ($expectedValue) {
            $expectedValue = $this->castTo($expectedValue, $attributeType);
        }
        $result = true;
        $i = 0;
        foreach ($this->subject as $parser) {
            $result &= $parser->parseImpl($iterator, $expectedValue ? $expectedValue[$i++] : null, $subtype, $skipper);
            if ($result === false) {
                return false;
            }
            $this->assignTo($parser->getAttribute(), $subtype);
        }
        // @todo: Not sure about this one either
        if ($attributeType !== $subtype) {
            $this->assignTo($this->attribute, $attributeType);
        }
        return true;
    }
}
