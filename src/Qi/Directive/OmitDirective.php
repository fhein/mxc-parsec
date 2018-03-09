<?php

namespace Mxc\Parsec\Qi\Directive;

class OmitDirective extends Directive
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return $this->subject->parseImpl($iterator, $skipper, 'NULL');
    }
}
