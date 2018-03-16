<?php

namespace Mxc\Parsec\Qi\Directive;

class OmitDirective extends Directive
{
    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return $this->subject->parse($iterator, $skipper, 'NULL');
    }
}
