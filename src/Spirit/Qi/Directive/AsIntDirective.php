<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\DelegatingParser;
use Mxc\Parsec\Qi\Parser;

class AsIntDirective extends DelegatingParser
{
    public function doParse($skipper)
    {
        $subject = $this->getSubject();
        if ($subject->parse($skipper)) {
            $this->assignTo($subject->getAttribute(), 'integer');
            return true;
        }
        return false;
    }

    public function getAttribute()
    {
        return Parser::getAttribute();
    }

    public function peekAttribute()
    {
        return Parser::peekAttribute();
    }
}
