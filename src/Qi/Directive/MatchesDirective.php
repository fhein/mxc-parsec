<?php

namespace Mxc\Parsec\Qi\Directive;

class MatchesDirective extends Directive
{

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->assignTo($this->subject->parse($iterator, $skipper, null), $attributeType);

        return true;
    }
}
