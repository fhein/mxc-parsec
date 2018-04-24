<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class SequenceOperator extends NaryParser
{
    public function doParse($skipper)
    {
        $result = true;
        $i = 0;
        foreach ($this->subject as $parser) {
            $result = $result && $parser->parse($skipper);

            if ($result === false) {
                return false;
            }
            $x = $parser->getAttribute();
//            var_dump($x);
            $this->attribute[] = $x;
//             print("----\n");
//             var_dump($this->attribute);
        }
        return true;
    }
}
