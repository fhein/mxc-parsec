<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\NaryParser;

class AlternativeOperator extends NaryParser
{
    public function doParse($skipper)
    {
        // return on first match
        $assignment = null;
        foreach ($this->subject as $parser) {
            if ($parser->parse($skipper)) {
                $this->attribute = $parser->getAttribute();
                return true;
            }
        }
        return false;
    }
}
