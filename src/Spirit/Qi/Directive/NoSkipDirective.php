<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;
use Mxc\Parsec\Qi\DelegatingParser;

class NoSkipDirective extends DelegatingParser
{
    public function doParse($skipper)
    {
        $this->skipOver($skipper);
        return parent::doParse(new UnusedSkipper($skipper));
    }
}
