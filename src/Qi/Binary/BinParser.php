<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Qi\PrimitiveParser;
use Mxc\Parsec\Qi\Domain;

class BinParser extends PrimitiveParser
{
    protected $endianness = null;
    protected $size = null;
    protected $expectedValue = null;

    public function __construct(Domain $domain, $expectedValue)
    {
        parent::__construct($domain);
        $this->expectedValue = $expectedValue;
    }

    public function doParse($skipper)
    {
        $iterator = $this->iterator;
        if ($iterator->getInputSize() >= $this->size) {
            $iterator->setBinary(true, $this->size);
            $this->attribute = unpack($this->endianness, $iterator->current())[1];
            $iterator->next();
            $iterator->setBinary(false);
            return ($this->expectedValue === null) || ($this->attribute === $this->expectedValue);
        }
        return false;
    }

    public function __debugInfo()
    {
        return array_merge_recursive(
            parent::__debugInfo(),
            [
                'endianness'    => $this->endianness ?? 'n/a',
                'size'          => $this->size ?? 0,
                'expectedValue' => $this->expectedValue ?? 'all'
            ]
        );
    }
}
