<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\Directive\Detail\ExpectationFailedException;
use Mxc\Parsec\Qi\DelegatingParser;

class ExpectDirective extends DelegatingParser
{
    public function doParse($skipper)
    {
        if (! $this->getSubject()->parse($skipper)) {
            $info = $this->what();
            throw new ExpectationFailedException(sprintf("Expecation failed: %s, %s", $info[0], $info[1]));
            return false;
        }
        return true;
    }
}
