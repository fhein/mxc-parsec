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

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return $this->parser->parseImpl($iterator, $skipper, null);
    }
}
