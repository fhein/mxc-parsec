<?php

namespace Mxc\Parsec\Qi;

abstract class DelegatingParser extends UnaryParser
{
    public function doParse($skipper)
    {
        return $this->getSubject()->parse($skipper);
    }

    public function getAttribute()
    {
        return $this->getSubject()->getAttribute();
    }

    public function peekAttribute()
    {
        return $this->getSubject()->peekAttribute();
    }
}
