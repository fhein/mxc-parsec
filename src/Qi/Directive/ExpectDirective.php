<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\Directive\Detail\ExpectationFailedException;
use Mxc\Parsec\Qi\ParserDelegator;

class ExpectDirective extends ParserDelegator
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if (! $this->subject->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            $info = $this->what();
            throw new ExpectationFailedException(sprintf("Expecation failed: %s, %s", $info[0], $info[1]));
            return false;
        }
        return true;
    }
}
