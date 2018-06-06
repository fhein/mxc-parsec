<?php

namespace Mxc\Parsec\Qi;

abstract class PrimitiveParser extends Parser
{
    public function parse($skipper = null)
    {
        $this->try();
        return $this->done($this->doParse($skipper));
    }
}
