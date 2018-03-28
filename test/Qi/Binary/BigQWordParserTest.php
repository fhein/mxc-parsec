<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\BigQWordParser;

class BigQWordParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n",
            $parser
        );
    }

    /** @dataProvider bigQWordDataProvider */
    public function testBigQWordParser($input, $expectedResult, $expectedValue = null)
    {
        $cfg = $this->getParserConfig(BigQWordParser::class);
        $parser = $this->pm->build(BigQWordParser::class);
        self::assertInstanceOf(BigQWordParser::class, $parser);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue      // expected value
        );
    }

    public function bigQWordDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false ],
            [ "\x01\x02", false ],
            [ "\x01\x02\x03", false ],
            [ "\x01\x02\x03\x04", false ],
            [ "\x01\x02\x03\x04\x05", false ],
            [ "\x01\x02\x03\x04\x05\x06", false ],
            [ "\x01\x02\x03\x04\x05\x06\x07", false ],
            [ "\x01\x02\x03\x04\x05\x06\x07\x08", true, unpack("J", "\x01\x02\x03\x04\x05\x06\x07\x08")[1] ],
            [ "\x01\x02\x03\x04\x05\x06\x07\x08", true, null, unpack("J", "\x01\x02\x03\x04\x05\x06\x07\x08")[1] ],
        ];
    }
}
