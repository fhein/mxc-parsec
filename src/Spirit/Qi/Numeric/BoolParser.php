<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Qi\DelegatingParser;
use Mxc\Parsec\Qi\Numeric\Detail\BoolPolicy;

class BoolParser extends DelegatingParser
{
    public function __construct(Domain $domain, string $uid, BoolPolicy $policy = null)
    {
        $this->defaultType = 'boolean';
        $policy = $policy ?? new BoolPolicy();
        parent::__construct($domain, $uid, new SymbolsParser($domain, $uid, $policy->getSymbols()));
    }
}
