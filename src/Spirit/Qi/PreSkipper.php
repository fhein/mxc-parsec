<?php

namespace Mxc\Parsec\Qi;

abstract class PreSkipper extends Parser
{
    public function parse($skipper = null)
    {
        $this->try();
        $this->skipOver($skipper);
        $result = $this->done($this->doParse($skipper));
        return $result;
    }
}
