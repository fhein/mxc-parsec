<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnaryParser;

abstract class Directive extends UnaryParser
{

    public function what()
    {
        return [ parent::what(), $this->subject->what()];
    }

    public function getAttribute()
    {
        return $this->subject->getAttribute();
    }
}
