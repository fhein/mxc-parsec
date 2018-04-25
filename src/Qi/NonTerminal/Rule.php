<?php

namespace Mxc\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\DelegatingParser;
use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\Domain;

class Rule extends DelegatingParser
{
    protected $poolId = null;

    public function __construct(Domain $domain, string $name, Parser $subject, string $attributeType = null)
    {
        parent::__construct($domain, $subject);
        $this->name = $name;
        $this->attributeType = $attributeType;
        $this->poolId = $this->domain->registerRule($this);
    }

    public function doParse($skipper)
    {
//         print("\n".'Parsing rule '.$this->getName().". Attribute type: ".$this->attributeType. ". ");

        if ($this->subject->parse($skipper)) {
            $this->assignTo($this->subject->getAttribute(), $this->attributeType);
            return true;
        }
        return false;
    }

    public function getAttribute()
    {
        return Parser::getAttribute();
    }
}
