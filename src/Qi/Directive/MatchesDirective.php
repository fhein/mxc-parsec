<?php

namespace Mxc\Parsec\Qi\Directive;

class MatchesDirective extends Directive
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->assignTo($this->subject->parseImpl($iterator, $skipper, null), $attributeType);

        return true;
    }
}
