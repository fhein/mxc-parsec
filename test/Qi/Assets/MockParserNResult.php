<?php
namespace Mxc\Test\Parsec\Qi\Assets;

use Mxc\Parsec\Domain;

class MockParserNResult extends MockParserDoParse
{
    protected $n;
    protected $count = 0;

    public function __construct(Domain $domain, $n = 1, $result = true)
    {
        parent::__construct($domain, $result);
        $this->n = $n;
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if ($this->count < $this->n) {
            $this->count++;
            return parent::doParse($iterator, $expectedValue, $attributeType, $skipper);
        }
        return ! $this->result;
    }
}
