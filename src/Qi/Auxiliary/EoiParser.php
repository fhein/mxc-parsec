<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PreSkipper;

class EoiParser extends PreSkipper
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return (! $iterator->valid());
    }
}
