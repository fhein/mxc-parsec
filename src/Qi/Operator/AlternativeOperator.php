<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class AlternativeOperator extends NaryParser
{
    public function doParse($skipper)
    {
        // return on first match
        $assignment = null;
        for ($i = 0; $i < $this->count; $i++) {
            $subject = $this->getSubject($i);
            if ($subject->parse($skipper)) {
                $this->attribute = $subject->getAttribute();
                return true;
            }
        }
        return false;
    }
}
