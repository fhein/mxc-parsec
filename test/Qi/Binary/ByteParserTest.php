<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\ByteParser;

class ByteParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, $expectedValue)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "   Expected Value: %s",
            $parser,
            strval($expectedValue)
        );
    }

    public function testByteParser()
    {
        $cfg = $this->getParserConfig(ByteParser::class, 1);
        $parser = $this->pm->build(ByteParser::class, [1]);

        $input = "\x01";
        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            true,               // expected result
            1                   // expectedValue
        );

        $input = "\x01";
        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            '',                 // parser input
            false               // expected result
        );
    }
}
