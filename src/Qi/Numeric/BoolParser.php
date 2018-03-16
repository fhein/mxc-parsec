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
        $this->setPolicy($policy);
        $this->defaultType = 'boolean';
        $this->delegateType = 'unused';
    }

    public function setPolicy(BoolPolicy $policy = null)
    {
        unset($this->delegate);
        $policy = $policy ?? new BoolPolicy();
        $this->delegate = new SymbolsParser($this->domain, $policy->getSymbols());
    }
}
