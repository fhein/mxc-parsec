<?php
namespace Mxc\Test\Parsec\Qi\Assets;

class MockParserParse extends MockParserDoParse
{
    public function parse($iterator, $skipper)
    {
        return $this->result;
    }
}
