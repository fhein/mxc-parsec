<?php

namespace Mxc\Parsec\Qi\Numeric;

class ShortParser extends IntParser
{
    protected $minValue = -32768;
    protected $maxValue = 32767;

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if (parent::doParse($iterator, $expectedValue, $attributeType, $skipper)) {
            return ($this->minValue <= $this->attribute && $this->attribute <= $this->maxValue);
        }
    }
}
