<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\DelegatingParser;

class NoCaseDirective extends DelegatingParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->domain->setNoCase(true);
        $result = parent::doParse($iterator, $expectedValue, $attributeType, $skipper);
        $this->domain->restoreNoCaseSetting();
        return $result;
    }
}
