<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PrimitiveParser;
use Mxc\Parsec\Qi\Domain;

class AttrParser extends PrimitiveParser
{

    public function __construct(Domain $domain, string $uid, $attribute)
    {
        parent::__construct($domain, $uid);
        $this->attribute = $attribute;
    }

    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    public function doParse($skipper)
    {
        return true;
    }
}
