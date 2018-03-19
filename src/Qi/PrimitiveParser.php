<?php

namespace Mxc\Parsec\Qi;

abstract class PrimitiveParser extends Parser
{
    public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        if (! $iterator->valid()) {
            return false;
        }

        $iterator->try();

        $this->skipOver($iterator, $skipper);

        if ($this->doParse($iterator, $expectedValue, $attributeType, $skipper)) {
            return $iterator->done($this->checkResult($expectedValue, $this->attribute, $attributeType));
        }

        $iterator->reject();
        return false;
    }
}
