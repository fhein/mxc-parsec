<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;
use Mxc\Parsec\Qi\DelegatingParser;

class LexemeDirective extends DelegatingParser
{
    public function doParse($skipper)
    {
        $unused = new UnusedSkipper($this->domain, '0', $skipper);
        $this->skipOver($skipper);
        return parent::doParse($unused);
    }
}
