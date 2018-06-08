<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\DelegatingParser;

class RawDirective extends DelegatingParser
{
    public function doParse($skipper)
    {
        $this->skipOver($skipper);
        $first = $this->iterator->getPos();
        if (parent::doParse($skipper)) {
            $this->attribute = $this->iterator->getSubStr($first, $this->iterator->key() - $first);
            return true;
        }
        return false;
    }

    // Revoke getAttribute redirection introduced by DelegatingParser
    public function getAttribute()
    {
        return Parser::getAttribute();
    }

    public function peekAttribute()
    {
        return Parser::peekAttribute();
    }
}
