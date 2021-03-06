<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Numeric\Detail\BoolPolicy;

class FalseParser extends BoolParser
{
    public function __construct(Domain $domain)
    {
        parent::__construct($domain, new BoolPolicy());
    }

    public function doParse($skipper)
    {
        if (parent::doParse($skipper)) {
            return $this->subject->attribute === false;
        }
    }
}
