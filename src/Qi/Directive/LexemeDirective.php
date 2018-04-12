<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;
use Mxc\Parsec\Qi\DelegatingParser;

class LexemeDirective extends DelegatingParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $unused = new UnusedSkipper($this->domain, $skipper);
        $this->skipOver($iterator, $skipper);
        return parent::doParse($iterator, $expectedValue, $attributeType, $unused);
    }
}
