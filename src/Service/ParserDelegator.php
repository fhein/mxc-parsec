<?php

namespace Mxc\Parsec\Service;

use Mxc\Parsec\Qi\Parser;

class ParserDelegator
{
    protected $parser;
    protected $options;

    public function __construct(Parser $parser, array $options = null)
    {
        $this->parser = $parser;
        $this->options = $options;
    }
}
