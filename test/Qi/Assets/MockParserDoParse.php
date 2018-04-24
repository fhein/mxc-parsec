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

    public function doParse($skipper)
    {
        if ($this->result) {
            return true;
        }
        return false;
    }

    public function parse($skipper = null)
    {
        return $this->doParse($this->iterator, $skipper);
    }

    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }
}
