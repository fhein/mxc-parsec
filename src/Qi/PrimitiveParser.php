<?php

namespace Mxc\Parsec\Qi;

abstract class PrimitiveParser extends Parser
{
    public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        $iterator->try();
        return $iterator->done($this->doParse($iterator, $expectedValue, $attributeType, $skipper));
    }
}
