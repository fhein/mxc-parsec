<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PrimitiveParser;
use Mxc\Parsec\Domain;

class AttrParser extends PrimitiveParser
{

    public function __construct(Domain $domain, $attribute)
    {
        $this->attribute = $attribute;
    }

    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return true;
    }
}
