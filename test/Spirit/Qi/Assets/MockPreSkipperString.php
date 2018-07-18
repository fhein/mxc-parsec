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

    public function doParse($skipper)
    {
        $s = new StringParser($this->domain, 'test', $this->expectedValue);
        if ($s->doParse($skipper)) {
            $this->attribute = $s->getAttribute();
            return true;
        }
        return false;
    }
}
