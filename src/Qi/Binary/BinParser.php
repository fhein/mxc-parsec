<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\PrimitiveParser;

class BinParser extends PrimitiveParser
{
    protected $endianness = null;
    protected $size = null;

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if ($iterator->getInputSize() >= $this->size) {
            $iterator->setBinary(true, $this->size);
            $value = unpack($this->endianness, $iterator->current())[1];
            if ($expectedValue === null || ($expectedValue === $value)) {
                $this->assignTo($value, $attributeType);
                $iterator->next();
                $iterator->setBinary(false);
                return true;
            }
            $iterator->setBinary(false);
        }
        return false;
    }
}
