<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;

class LexemeDirective extends PassThroughDirective
{

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->skipOver($iterator, $skipper);
        if ($this->subject->parse($iterator, new UnusedSkipper($skipper), $attributeType)) {
            return true;
        }
        return false;
    }
}
