<?php

namespace Mxc\Parsec\Qi;

class PredicateParser extends UnaryParser
{
    public function parse($skipper = null)
    {
        // predicate parser do not consume any input
        $this->iterator->try();
        $result = $this->doParse($skipper);
        $this->iterator->reject();
        return $result;
    }
}
