<?php

namespace Mxc\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\UnaryParser;

class Rule extends UnaryParser
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
