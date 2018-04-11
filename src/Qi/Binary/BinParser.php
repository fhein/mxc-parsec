<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\PrimitiveParser;
use Mxc\Parsec\Domain;

class BinParser extends PrimitiveParser
{
    protected $endianness = null;
    protected $size = null;

    public function __construct(Domain $domain)
    {
        parent::__construct($domain);
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
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

    public function __debugInfo()
    {
        return array_merge_recursive(
            parent::__debugInfo(),
            [
                'endianness' => $this->endianness ?? 'n/a',
                'size'       => $this->size ?? 0,
            ]
        );
    }
}
