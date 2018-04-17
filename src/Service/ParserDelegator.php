<?php

namespace Mxc\Parsec\Service;

use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Domain;

class ParserDelegator
{
    protected $domain;
    protected $parser;
    protected $expectedValue;
    protected $attributeType;
    protected $options;
    protected $iterator;

    public function __construct(
        Domain $domain,
        Parser $parser,
        $expectedValue,
        $attributeType = null,
        $skipper = null,
        array $options = null
    ) {

        parent::__construct($domain, $parser);
        $this->iterator = $domain->getInputIterator();
        $this->parser = $parser;
        $this->expectedValue = $expectedValue;
        $this->attributeType = $attributeType;
        $this->options = $options;
    }

    public function doParse()
    {
        return $this->parser->doParse($this->iterator, $this->expectedValue, $this->attributeType, $this->skipper);
    }

    public function parse()
    {
        return $this->parser->parse($this->iterator, $this->expectedValue, $this->attributeType, $this->skipper);
    }
}
