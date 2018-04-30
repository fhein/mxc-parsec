<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;
use Mxc\Parsec\Qi\Directive\Detail\ExpectationFailedException;

class ExpectOperator extends NaryParser
{
    public function doParse($skipper)
    {
        $subject = $this->subject;
        $lhs = $this->getSubject(0);

        if (! $lhs->parse($skipper)) {
            return false;
        }

        $last = count($subject);
        $this->attribute = $lhs->getAttribute();

        for ($idx = 1; $idx < $last; $idx++) {
            $rhs = $this->getSubject($idx);
            if (! $rhs->parse($skipper)) {
                $info = $this->what();
                throw new ExpectationFailedException(sprintf(" %s, %s: Expectation failed.", $info[0], $info[1]));
                return false;
            }
            $this->attribute = $rhs->getAttribute();
        }
        return true;
    }
}
