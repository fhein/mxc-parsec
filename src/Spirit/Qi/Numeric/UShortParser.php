<?php

namespace Mxc\Parsec\Qi\Numeric;

class UShortParser extends UIntParser
{
    protected $minValue = 0;
    protected $maxValue = 65536;

    public function doParse($skipper)
    {
        if (parent::doParse($skipper)) {
            return ($this->minValue <= $this->attribute && $this->attribute <= $this->maxValue);
        }
    }
}
