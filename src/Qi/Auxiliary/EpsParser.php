<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PrimitiveParser;
use Mxc\Parsec\Domain;

class EpsParser extends PrimitiveParser
{
    protected $callable;

    public function __construct(Domain $domain, $callable = null)
    {
        $this->callable = $callable;
        parent::__construct($domain);
    }

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        return (is_callable($this->callable)) ? ($this->callable)() : true;
    }
}
