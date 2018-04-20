<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PrimitiveParser;
use Mxc\Parsec\Qi\Domain;

class AttrParser extends PrimitiveParser
{

    public function __construct(Domain $domain, $attribute)
    {
        parent::__construct($domain);
        $this->attribute = $attribute;
    }

    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->assignTo($expectedValue, $attributeType);
        return true;
    }
}
