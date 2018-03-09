<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;
use Mxc\Parsec\Qi\Parser;

class SkipDirective extends PassthroughDirective
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if (isset($this->args[0]) && ($this->args[0] instanceof Parser)) {
            $skipper = $this->args[0];
        } elseif ($skipper instanceof UnusedSkipper) {
            $skipper = $skipper->getSkipper();
        }
        return $this->subject->parseImpl($iterator, $expectedValue, $attributeType. $skipper);
    }
}
