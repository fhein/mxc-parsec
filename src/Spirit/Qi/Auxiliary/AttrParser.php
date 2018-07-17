<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PrimitiveParser;
use Mxc\Parsec\Qi\Domain;

class AttrParser extends PrimitiveParser
{

    public function __construct(Domain $domain, string $uid, $type, $value)
    {
        parent::__construct($domain, $uid);
        $this->attribute = $this->castTo($type, $value);
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
