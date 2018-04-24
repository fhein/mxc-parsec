<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class PermutationOperator extends NaryParser
{
    public function doParse($skipper)
    {
        $result = false;
        $taken = [];
        $matched = 0;
        do {
            cont:
            foreach ($this->subject as $key => $parser) {
                if (! isset($taken[$key]) && $parser->parse($skipper)) {
                    $this->attribute[] = $parser->getAttribute();
                    $matched++;
                    $taken[$key] = true;
                    $result = true;
                    continue 2;
                }
            }
            break;
        } while (true);
        return $result;
    }
}
