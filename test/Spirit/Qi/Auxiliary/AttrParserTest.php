<?php

namespace Mxc\Test\Parsec\Qi\Auxiliary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Auxiliary\AttrParser;

class AttrParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, $attribute)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    Attribute: %s\n",
            $parser,
            var_export($attribute, true)
        );
    }

    public function testAttrParser()
    {
        $uid = 'test';
        $input = 'abc';
        $expectedValue = 1;

        $cfg = $this->getParserConfig(AttrParser::class, $expectedValue);
        $parser = $this->pm->build(AttrParser::class, [ $uid, 'integer', $expectedValue ]);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            true,               // expected result
            $expectedValue,     // expected value
            $expectedValue      // expected attribute
        );
    }
}
