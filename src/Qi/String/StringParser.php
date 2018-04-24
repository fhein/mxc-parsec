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

    public function doParse($skipper)
    {
        $string = $this->domain->getInternalIterator($this->string);
        return $this->iterator->parseString($string, $this->attribute);
    }
}
