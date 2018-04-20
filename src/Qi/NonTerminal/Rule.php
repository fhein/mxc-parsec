<?php

namespace Mxc\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\DelegatingParser;

class Rule extends DelegatingParser
{
    protected $poolId = null;

    public function __construct($domain, $name, $subject, string $attributeType = null)
    {
        parent::construct($domain, $subject);
        $this->name = $name;
        $this->attributeType = $attributeType;
        $this->poolId = $this->domain->registerRule($this);
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return $this->subject->parse($iterator, $expectedValue, $this->attributeType, $skipper);
    }
}
