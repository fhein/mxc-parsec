<?php

namespace Mxc\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\Parser;

class Grammar extends Parser
{
    public function __construct($domain, $name, $expression)
    {
        parent::construct($domain);
        $this->name = $name;
        $this->expression = $expression;
    }

    public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
    }
}
