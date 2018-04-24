<?php

namespace Mxc\Parsec\Qi;

abstract class PreSkipper extends Parser
{
    public function parse($skipper = null)
    {
        $this->iterator->try();
        $this->skipOver($skipper);
        $result = $this->iterator->done($this->doParse($skipper));
        return $result;
    }
}
