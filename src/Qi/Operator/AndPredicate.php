<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\PredicateParser;
use Mxc\Parsec\Qi\Parser;

class AndPredicate extends PredicateParser
{

    protected $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return $this->parser->parse($iterator, $skipper, null);
    }
}
