<?php

namespace Mxc\Parsec\Qi\Binary;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\PrimitiveParser;

class BinParser extends PrimitiveParser
{

    protected $value = null;
    protected $endianness = null;

    public function __construct(Domain $domain, int $value = null)
    {
        parent::__construct($domain);
        $this->value = $value === null ? null : pack($this->endianness, $value);
    }

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $iterator->setBinary(true, $this->size);
        if ($iterator->valid()) {
            $value = $iterator->current();
            if ($this->value === null || ($this->value == $value)) {
                $attr = unpack($this->endianness, $value)[1];
                print $attr . " ";
                $this->assignTo(unpack($this->endianness, $value)[1], $attributeType);
                $iterator->next();
                $iterator->setBinary(false);
                return true;
            }
        }
        $iterator->setBinary(false);
        return false;
    }
}
