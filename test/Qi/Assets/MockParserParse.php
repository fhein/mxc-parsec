<?php
namespace Mxc\Test\Parsec\Qi\Assets;

class MockParserParse extends MockParserDoParse
{
    public function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if ($this->result) {
            $this->assignTo($this->attribute, $attributeType);
            return true;
        }
        return false;
    }
}
