<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\PreSkipper;

class Char extends PreSkipper
{

    protected $classifier;
    protected $defaultType = 'string';

    public function doParse($iterator, $expectedValue = null, $attributeType = 'string', $skipper = null)
    {
        if (! $iterator->valid()) {
            return false;
        }
        $c = $iterator->current();
        if (($this->classifier)($c)) {
            $this->assignTo($c, $attributeType);
            $iterator->next();
            return true;
        }
        return false;
    }
}
