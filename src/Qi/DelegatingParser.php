<?php

namespace Mxc\Parsec\Qi;

abstract class DelegatingParser extends UnaryParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return $this->subject->parse($iterator, $expectedValue, $attributeType, $skipper);
    }

    public function getAttribute()
    {
        return $this->subject->getAttribute();
    }
}
