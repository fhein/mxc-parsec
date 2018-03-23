<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Exception\BadMethodCallException;

abstract class ParserDelegator extends Parser
{

    const     MSG = '%s: Delegate not initialized.';

    protected $delegate = null;

    public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        if ($this->delegate === null) {
            throw new BadMethodCallException(sprintf(self::MSG, $this->what()));
        }
        return $iterator->try()->done($this->delegate->parse($iterator, $expectedValue, $attributeType, $skipper));
    }

    public function getAttribute()
    {
        if ($this->delegate === null) {
            throw new BadMethodCallException(sprintf(self::MSG, $this->what()));
        }
        return $this->delegate->getAttribute();
    }

    public function what()
    {
        return $this->delegate !== null ? [ parent::what(), $this->delegate->what()] : parent::what();
    }
}
