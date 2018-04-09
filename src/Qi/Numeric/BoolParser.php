<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Qi\ParserDelegator;
use Mxc\Parsec\Qi\Numeric\Detail\BoolPolicy;

class BoolParser extends ParserDelegator
{
    public function __construct(Domain $domain, BoolPolicy $policy = null)
    {
        parent::__construct($domain);
        $this->defaultType = 'boolean';
        $policy = $policy ?? new BoolPolicy();
        $this->subject = new SymbolsParser($this->domain, $policy->getSymbols());
    }
}
