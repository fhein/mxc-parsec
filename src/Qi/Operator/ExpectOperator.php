<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;
use Mxc\Parsec\Qi\Directive\Detail\ExpectationFailedException;

class ExpectOperator extends NaryParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subject = $this->subject;
        $lhs = $subject[0];

        if (! $lhs->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            return false;
        }

        $last = count($subject);
        $this->assignTo($lhs->getAttribute(), $attributeType);

        for ($idx = 1; $idx < $last; $idx++) {
            $rhs = $subject[$idx];
            if (! $rhs->parse($iterator, $expectedValue, $attributeType, $skipper)) {
                $info = $this->what();
                throw new ExpectationFailedException(sprintf(" %s, %s: Expectation failed.", $info[0], $info[1]));
                return false;
            }
            $this->assignTo($rhs->getAttribute(), $attributeType);
        }
        return true;
    }
}
