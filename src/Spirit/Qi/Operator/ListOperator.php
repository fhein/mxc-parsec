<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\BinaryParser;

class ListOperator extends BinaryParser
{
    protected $parsers;

    public function doParse($skipper)
    {
        $lhs = $this->subject[0];

        if (! $lhs->parse($skipper)) {
            return false;
        }
        $this->attribute[] = $lhs->getAttribute();
        $rhs = $this->subject[1];

        while (true) {
            $save = $this->iterator->key();
            if ($rhs->parse($skipper)
                && $lhs->parse($skipper)) {
                    $this->attribute[] = $lhs->getAttribute();
                    continue;
            }
            $this->iterator->setPos($save);
            break;
        }
        return true;
    }
}
