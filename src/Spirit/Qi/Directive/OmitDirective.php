<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\DelegatingParser;
use Mxc\Parsec\Attribute\Unused;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Parser;

class OmitDirective extends DelegatingParser
{
    public function __construct(Domain $domain, string $uid, $parser)
    {
        parent::__construct($domain, $uid, $parser);
        $this->attribute = new Unused;
    }

    public function doParse($skipper)
    {
        return parent::doParse($skipper);
    }

    public function getAttribute()
    {
        return Parser::getAttribute();
    }

    public function peekAttribute()
    {
        return Parser::peekAttribute();
    }
}
