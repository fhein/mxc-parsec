<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;

class NoSkipDirective extends PassThroughDirective
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subject = $this->subject;
        return $subject->parseImpl($iterator, new UnusedSkipper($skipper), $attributeType);
    }
}
