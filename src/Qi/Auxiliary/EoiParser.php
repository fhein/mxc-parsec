<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PreSkipper;

class EoiParser extends PreSkipper
{

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return (! $iterator->valid());
    }
}
