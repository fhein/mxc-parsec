<?php

namespace Mxc\Parsec\Qi;

abstract class PrimitiveParser extends Parser
{
    public function parse($skipper = null)
    {
        $this->iterator->try();
        return $this->iterator->done($this->doParse($skipper));
    }
}
