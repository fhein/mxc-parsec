<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\Directive\Detail\ExpectationFailedException;

class ExpectDirective extends Directive
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subject = $this->subject;
        if (! $subject->parseImpl($iterator, $expectedValue, $attributeType. $skipper)) {
            $info = $this->what();
            throw new ExpectationFailedException(sprintf("Expecation failed: %s, %s"), $info[0], $info[1]);
            return false;
        }
        $this->assignTo($subject->getAttribute(), $attributeType);
        return true;
    }
}
