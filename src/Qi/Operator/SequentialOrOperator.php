<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class SequentialOrOperator extends NaryParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $result = false;
        $current = 0;
        if (! isset($this->subject[$current])) {
            print('Kacke');
            return false;
        }
        $subject = $this->subject[$current];
        if ($subject->parse($iterator, null, null, $skipper)) {
            $this->assignTo($subject->getAttribute(), 'array');
            $result = true;
        }
        $current++;
        while (isset($this->subject[$current])) {
            $subject = $this->subject[$current];
            if ($subject->parse($iterator, null, null, $skipper)) {
                $this->assignTo($subject->getAttribute(), 'array');
                $result = true;
            } elseif (! $result) {
                return false;
            }
            $current++;
        };
        return $result;
    }
}
