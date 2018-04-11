<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\ParserDelegator;

class NoCaseDirective extends ParserDelegator
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->domain->setNoCase(true);
        $result = parent::parse($iterator, $expectedValue, $attributeType, $skipper);
        $this->domain->restoreNoCaseSetting();
        return $result;
    }
}
