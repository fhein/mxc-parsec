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
    public function __construct(Domain $domain, string $uid, $expectedValue)
    {
        if (is_string($expectedValue)) {
            parent::__construct($domain, $uid, new StringParser($domain, $uid, $expectedValue));
        } elseif (is_int($expectedValue)) {
            parent::__construct($domain, $uid, new LongLongParser($domain, $uid, $expectedValue));
        } elseif (is_float($expectedValue)) {
            parent::__construct($domain, $uid, new LongDoubleParser($domain, $uid, $expectedValue));
        } else {
            throw new InvalidArgumentException('Invalid argument for LitParser.');
        }
    }

    public function doParse($skipper)
    {
        return $this->getSubject()->parse($skipper);
    }
}
