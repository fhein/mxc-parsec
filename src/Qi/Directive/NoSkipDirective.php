<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;
use Mxc\Parsec\Qi\ParserDelegator;

class NoSkipDirective extends ParserDelegator
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return parent::doParse($iterator, $expectedValue, $attributeType, new UnusedSkipper($skipper));
    }
}
