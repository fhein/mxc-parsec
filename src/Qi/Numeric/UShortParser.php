<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;

class UShortParser extends UIntParser
{
    protected $minValue = 0;
    protected $maxValue = 65536;

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if (parent::doParse($iterator, $expectedValue, $attributeType, $skipper)) {
            return ($this->minValue <= $this->attribute && $this->attribute <= $this->maxValue);
        }
    }
}
