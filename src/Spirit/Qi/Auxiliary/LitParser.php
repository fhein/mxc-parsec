<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Qi\DelegatingParser;
use Mxc\Parsec\Qi\Numeric\LongDoubleParser;
use Mxc\Parsec\Qi\Numeric\LongLongParser;
use Mxc\Parsec\Qi\String\StringParser;

class LitParser extends DelegatingParser
{
    public function __construct(Domain $domain, $expectedValue)
    {
        if (is_string($expectedValue)) {
            parent::__construct($domain, new StringParser($domain, $expectedValue));
        } elseif (is_int($expectedValue)) {
            parent::__construct($domain, new LongLongParser($domain, $expectedValue));
        } elseif (is_float($expectedValue)) {
            parent::__construct($domain, new LongDoubleParser($domain, $expectedValue));
        } else {
            throw new InvalidArgumentException('Invalid argument for LitParser.');
        }
    }

    public function doParse($skipper)
    {
        return $this->subject->parse($skipper);
    }
}
