<?php
namespace Mxc\Test\Parsec\Qi\Assets;

use Mxc\Parsec\Qi\PreSkipper;
use Mxc\Parsec\Qi\String\StringParser;
use Mxc\Parsec\Qi\Domain;

class MockPreSkipperString extends PreSkipper
{
    protected $expectedValue;

    public function __construct(Domain $domain, $expectedValue = null)
    {
        parent::__construct($domain);
        $this->expectedValue = $expectedValue ?? 'abc';
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $s = new StringParser($this->domain, $this->expectedValue);
        if ($s->doParse($iterator, $this->expectedValue, $attributeType)) {
            $this->assignTo($s->getAttribute(), $attributeType);
            return true;
        }
        return false;
    }
}
