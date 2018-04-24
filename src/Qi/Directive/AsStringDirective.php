<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\DelegatingParser;

class AsStringDirective extends DelegatingParser
{
    public function doParse($skipper)
    {
        return $this->subject->parse($skipper);
    }
}
