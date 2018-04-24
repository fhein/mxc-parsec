<?php

namespace Mxc\Parsec\Qi\Operator;

use Mxc\Parsec\Qi\NaryParser;
use Mxc\Parsec\Attribute\Optional;
use Mxc\Parsec\Attribute\Unused;

class SequentialOrOperator extends NaryParser
{
    public function doParse($skipper)
    {
        $result = false;
        $current = 0;
        $subject = $this->subject[$current];
        $unused = new Unused();
        if ($subject->parse($skipper)) {
            $this->attribute[] = new Optional($subject->getAttribute());
            $result = true;
        } else {
            $this->attribute[] = new Optional($unused);
        }
        $current++;
        while (isset($this->subject[$current])) {
            $subject = $this->subject[$current];
            if ($subject->parse($skipper)) {
                $this->attribute[] = new Optional($subject->getAttribute());
                $result = true;
            } elseif ($result) {
                $this->attribute[] = new Optional($unused);
            } else {
                return false;
            }
            $current++;
        };
        return $result;
    }
}
