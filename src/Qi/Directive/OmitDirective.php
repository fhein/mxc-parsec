<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\ParserDelegator;

// @todo: May be wrong to derive from ParserDelegator
class OmitDirective extends ParserDelegator
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return parent::parse($iterator, null, 'unused', $skipper);
    }
}
