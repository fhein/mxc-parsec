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

    public function parseImpl($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        return $this->parser->parseImpl($iterator, $expectedValue, $attributeType, $skipper);
    }

    public function parse($iterator, $expectedValue, $skipper)
    {
        return $this->parser->parseImpl($iterator, $expectedValue, $this->type, $skipper);
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
