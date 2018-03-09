<?php

namespace Mxc\Parsec\Qi;

class UnusedSkipper extends Parser
{

    public function __construct(Parser $skipper = null)
    {
        $this->skipper = $skipper;
    }

    public function getSkipper()
    {
        return $this->skipper;
    }

    public function parseImpl($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        return false;
    }

    public function parse($iterator, $expectedValue = 0, string $attributeType = null, $skipper = null)
    {
        return false;
    }
}
