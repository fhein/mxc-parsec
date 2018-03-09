<?php

namespace Mxc\Parsec\Qi\Directive;

class HoldDirective extends PassthroughDirective
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subject = $this->subject;
        return $subject->parseImpl($iterator, $expectedValue, $attributeType. $skipper);
            // the hold directive is here only for compatibility
            // reasons

            // sub parsers do not modify the outer attribute
            // so ther is no need for a rollback
    }
}
