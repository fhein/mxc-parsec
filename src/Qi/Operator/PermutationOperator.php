<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class PermutationOperator extends NaryParser
{
    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $result = false;
        $taken = [];
        $matched = 0;
        $count = count($this->subject);
        $assignment = null;
        do {
            cont:
            foreach ($this->subject as $key => $parser) {
                if (! isset($taken[$key]) && $parser->parse($iterator, $expectedValue, $attributeType, $skipper)) {
                    if ($assignment === null) {
                        $assignment = $this->getAssignment($attributeType);
                    }
                    $this->assignTo($parser->getAttribute(), $attributeType);
                    $matched++;
                    $taken[$key] = true;
                    $result = true;
                    continue 2;
                }
            }
            break;
        } while (true);
        return $result;
    }
}
