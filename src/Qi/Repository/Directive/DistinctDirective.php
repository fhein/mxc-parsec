<?php

namespace Mxc\Parsec\Qi\Repository\Directive;

use Mxc\Parsec\Qi\BinaryParser;

class DistinctDirective extends BinaryParser
{
    public function doParse($skipper)
    {
        $subject = $this->subject[1];
        if ($subject->parse($skipper)
            && ! $this->subject[0]->doParse(null)) {
            $this->attribute = $subject->getAttribute();
            return true;
        }
        return false;
    }
}
