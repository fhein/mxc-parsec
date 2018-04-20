<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\BoolPolicy;

class TrueParser extends BoolParser
{
    public function __construct(Domain $domain)
    {
        parent::__construct($domain, new BoolPolicy());
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if (parent::doParse($iterator, $expectedValue, $attributeType, $skipper)) {
            return $this->subject->attribute === true;
        }
    }
}
