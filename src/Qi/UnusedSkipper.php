<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Qi\Domain;

class UnusedSkipper extends Parser
{
    protected $skipper;

    public function __construct(Domain $domain, Parser $skipper = null)
    {
        parent::__construct($domain);
        $this->skipper = $skipper;
    }

    public function getSkipper()
    {
        return $this->skipper;
    }

    public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        return false;
    }

    public function doParse($iterator, $expectedValue = 0, string $attributeType = null, $skipper = null)
    {
        return false;
    }

    public function __debugInfo()
    {
        return array_merge_recursive(
            parent::__debugInfo(),
            [
                'skipper' => $this->skipper ?? 'n/a',
            ]
        );
    }
}
