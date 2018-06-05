<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;

class SequenceOperator extends NaryParser
{
    public function doParse($skipper)
    {
        $result = true;
        foreach ($this->subject as $idx => $_) {
            $parser = $this->getSubject($idx);
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
