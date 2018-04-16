<?php

namespace Mxc\Parsec\Qi\Repository\Directive;

use Mxc\Parsec\Qi\BinaryParser;

class DistinctDirective extends BinaryParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $subject = $this->subject[1];
        if ($subject->parse($iterator, $expectedValue, $attributeType, $skipper)
            && ! $this->subject[0]->doParse($iterator, null, null, null)) {
            $this->assignTo($subject->getAttribute(), $attributeType);
            return true;
        }
        return false;
    }
}
