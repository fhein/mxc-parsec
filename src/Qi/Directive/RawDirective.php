<?php

namespace Mxc\Parsec\Qi\Directive;

class RawDirective extends Directive
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $first = $iterator->getPos();
        if ($this->subject->parseImpl($iterator, $skipper, null)) {
            $this->assignTo(['begin' => $first, 'end' => $iterator->key()], $attributeType);
            return true;
        }
        return false;
    }
}
