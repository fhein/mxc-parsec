<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PrimitiveParser;

class EpsilonParser extends PrimitiveParser
{

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->skipOver($iterator, $skipper);
        return (isset($args[0]) && is_callable($this->args[0])) ? $this->args[0]() : true;
    }
}
