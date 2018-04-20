<?php
namespace Mxc\Test\Parsec\Qi\Assets;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Parser;

class MockParserDoParse extends Parser implements MockParserInterface
{
    public function __construct(Domain $domain, $result = true, $attribute = null)
    {
        parent::__construct($domain);
        $this->attribute = $attribute;
        $this->result = $result;
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if ($this->result) {
            $this->assignTo($this->attribute, $attributeType);
            return true;
        }
        return false;
    }

    public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        return $this->doParse($iterator, $expectedValue, $attributeType, $skipper);
    }

    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }
}
