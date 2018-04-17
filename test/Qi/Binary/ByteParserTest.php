<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\ByteParser;

class ByteParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n",
            $parser
        );
    }

    public function testByteParser()
    {
        $cfg = $this->getParserConfig(ByteParser::class);
        $parser = $this->pm->build(ByteParser::class);

        $input = "\x01";
        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            true,               // expected result
            1
        );

        $input = "\x01";
        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            '',                 // parser input
            false              // expected result
        );
    }
}
