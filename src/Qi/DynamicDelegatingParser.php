<?php

namespace Mxc\Parsec\Qi;

abstract class DynamicDelegatingParser extends DelegatingParser
{
    public function doParse($skipper)
    {
        return $this->getSubject()->parse($skipper);
    }

    abstract protected function getSubject();
}
