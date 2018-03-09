<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;
use Mxc\Parsec\Qi\Directive\Detail\ExpectationFailedException;

class ExpectOperator extends NaryParser
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subject = $this->subject;
        $lhs = $subject[0];

        if (! $lhs->parseImpl($iterator, $expectedValue, $attributeType. $skipper)) {
            return false;
        }

        $last = count($subject);
        $this->assignTo($lhs->getAttribute(), $attributeType);

        for ($idx = 1; $idx < count; $idx++) {
            $rhs = $subject[$idx];
            if (! $rhs->parseImpl($iterator, $expectedValue, $attributeType. $skipper)) {
                $info = $this->what();
                throw new ExpectationFailedException(sprintf("Expecation failed: %s, %s"), $info[0], $info[1]);
                return false;
            }
            $this->assignTo($rhs->getAttribute(), $attributeType);
        }
        return true;
    }
}
