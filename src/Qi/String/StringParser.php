<?php

namespace Mxc\Parsec\Qi\String;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\PreSkipper;

class StringParser extends PreSkipper
{

    public function __construct(Domain $domain, string $string)
    {
        parent::__construct($domain);
        $this->string = $string;
    }

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $i = 0;
        $attr = null;
        $string = $this->domain->getInternalIterator($this->string);
        if ($iterator->parseString($string, $attr)) {
            $this->assignTo($attr, $attributeType);
            return true;
        }
        return false;
    }
}
