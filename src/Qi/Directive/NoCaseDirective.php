<?php

namespace Mxc\Parsec\Qi\Directive;

class NoCaseDirective extends PassThroughDirective
{

    protected function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->domain->setNoCase(true);
        $subject = $this->subject;
        $result = $subject->parse($iterator, $expectedValue, $attributeType, $skipper);
        $this->domain->restoreNoCaseSetting();
        return $result;
    }
}
