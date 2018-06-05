<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnaryParser;

class MatchesDirective extends UnaryParser
{
    public function doParse($skipper)
    {
        $this->attribute = $this->getSubject()->parse($skipper);
        return true;
    }
}
