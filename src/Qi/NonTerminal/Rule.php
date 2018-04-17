<?php

namespace Mxc\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\UnaryParser;

class Rule extends UnaryParser
{
    public function __construct($domain, $name, $subject, string $attributeType = null)
    {
        parent::construct($domain, $subject);
        $this->name = $name;
        $this->attributeType = $attributeType;
    }

    public function what()
    {
        return sprintf('%s (%s)', parent::what(), $this->name);
    }
}
