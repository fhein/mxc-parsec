<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;

class NoSkipDirective extends PassThroughDirective
{
    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subject = $this->subject;
        return $subject->parse($iterator, new UnusedSkipper($skipper), $attributeType);
    }
}
