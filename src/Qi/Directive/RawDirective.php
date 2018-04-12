<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\DelegatingParser;

class RawDirective extends DelegatingParser
{
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $this->skipOver($iterator, $skipper);
        $first = $iterator->getPos();
        if (parent::doParse($iterator, $expectedValue, 'unused', $skipper)) {
            if ($attributeType === 'string') {
                $this->attribute = $iterator->getData($first, $iterator->key() - $first);
                return true;
            }
            $this->attribute = [ $first, $iterator->key() ];
            return true;
        }
        return false;
    }

    // Revoke getAttribute redirection introduced by DelegatingParser
    public function getAttribute()
    {
        return parent::getAttribute();
    }
}
