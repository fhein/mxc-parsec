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
            foreach ($this->subject as $idx => $_) {
                $parser = $this->getSubject($idx);
                if (! isset($taken[$idx]) && $parser->parse($skipper)) {
                    $this->attribute[] = $parser->getAttribute();
                    $matched++;
                    $taken[$idx] = true;
                    $result = true;
                    continue 2;
                }
            }
            break;
        } while (true);
        return $result;
    }
}
