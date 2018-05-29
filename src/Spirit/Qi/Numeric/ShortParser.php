<?php

namespace Mxc\Parsec\Qi\Numeric;

class ShortParser extends IntParser
{
    protected $minValue = -32768;
    protected $maxValue = 32767;

    public function doParse($skipper)
    {
        if (parent::doParse($skipper)) {
            return ($this->minValue <= $this->attribute && $this->attribute <= $this->maxValue);
        }
    }
}
