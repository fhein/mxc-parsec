<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;
use Mxc\Parsec\Qi\DelegatingParser;

class NoSkipDirective extends DelegatingParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->skipOver($iterator, $skipper);
        return parent::doParse($iterator, $expectedValue, $attributeType, new UnusedSkipper($skipper));
    }
}
