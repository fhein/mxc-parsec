<?php

namespace Mxc\Parsec\Qi\String;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\PreSkipper;

class StringParser extends PreSkipper
{
    protected $string;

    public function __construct(Domain $domain, string $string)
    {
        parent::__construct($domain);
        $this->string = $string;
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $attr = null;
        $string = $this->domain->getInternalIterator($this->string);
        if ($iterator->parseString($string, $attr)) {
            $this->assignTo($attr, $attributeType);
            return true;
        }
        return false;
    }
}
