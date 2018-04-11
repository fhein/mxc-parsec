<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnaryParser;

class MatchesDirective extends UnaryParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->assignTo($this->subject->parse($iterator, $expectedValue, $attributeType, $skipper), 'boolean');
        return true;
    }
}
