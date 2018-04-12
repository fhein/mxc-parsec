<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\DelegatingParser;

class OmitDirective extends DelegatingParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return parent::doParse($iterator, $expectedValue, 'unused', $skipper);
    }
}
