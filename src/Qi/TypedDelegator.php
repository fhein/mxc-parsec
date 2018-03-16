<?php

namespace Mxc\Parsec\Qi;

class TypedDelegator extends Parser
{
    protected $parser;
    protected $type;
    public function __construct($parser, $type)
    {
        $this->parser = $parser;
        $this->type = $type;
    }

    public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        return $this->parser->parse($iterator, $expectedValue, $attributeType, $skipper);
    }

    public function doParse($iterator, $expectedValue, $skipper)
    {
        return $this->parser->doParse($iterator, $expectedValue, $this->type, $skipper);
    }

    public function what()
    {
        return $this->parser->what();
    }

    public function getAttribute()
    {
        return $this->parser->getAttribute();
    }
}
