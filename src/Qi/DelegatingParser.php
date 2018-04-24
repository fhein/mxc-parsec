<?php

namespace Mxc\Parsec\Qi;

abstract class DelegatingParser extends UnaryParser
{
    public function doParse($skipper)
    {
        return $this->subject->parse($skipper);
    }

    public function getAttribute()
    {
        return $this->subject->getAttribute();
    }
}
