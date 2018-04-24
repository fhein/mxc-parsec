<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\DelegatingParser;

class NoCaseDirective extends DelegatingParser
{
    public function doParse($skipper)
    {
        $this->iterator->setNoCase(true);
        $result = parent::doParse($skipper);
        $this->iterator->setNoCase();
        return $result;
    }
}
