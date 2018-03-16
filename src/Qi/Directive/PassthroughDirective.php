<?php

namespace Mxc\Parsec\Qi\Directive;

abstract class PassThroughDirective extends Directive
{
    public function getAttribute()
    {
        return $this->subject->getAttribute();
    }
}
