<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;
use Mxc\Parsec\Qi\ParserDelegator;

class LexemeDirective extends ParserDelegator
{
    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->skipOver($iterator, $skipper);
        return parent::doParse($iterator, $expectedValue, $attributeType, new UnusedSkipper($skipper));
    }
}
