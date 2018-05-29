<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PreSkipper;

class EoiParser extends PreSkipper
{
    public function doParse($skipper)
    {
        return (! $this->iterator->valid());
    }
}
