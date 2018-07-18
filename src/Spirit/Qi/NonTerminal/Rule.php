<?php

namespace Mxc\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\DelegatingParser;
use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\Domain;

class Rule extends DelegatingParser
{
    public function __construct(
        Domain $domain,
        string $uid,
        string $name,
        $subject,
        $skipper,
        string $attributeType = null
    ) {
        parent::__construct($domain, $uid, $subject);
        $this->name = $name;
        $this->attributeType = $attributeType;

        $this->skipper = is_string($skipper) ? $this->domain->getParser($skipper) : $skipper;
    }

    public function doParse($skipper)
    {
//         print("\n".'Parsing rule '.$this->getName().". Attribute type: ".$this->attributeType. ". ");
        // @todo: Skipper handling to be reviewed
        if ($this->getSubject()->parse($this->skipper)) {
            $this->assignTo($this->subject->getAttribute(), $this->attributeType);
            return true;
        }
        return false;
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
