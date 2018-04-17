<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\BigDWordParser;

class BigDWordParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n",
            $parser
        );
    }

    /** @dataProvider bigDWordDataProvider */
    public function testbigDWordParser($input, $expectedResult, $expectedValue = null)
    {
        $cfg = $this->getParserConfig(BigDWordParser::class);
        $parser = $this->pm->build(BigDWordParser::class);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue      // expected value
        );
    }

    public function bigDWordDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false ],
            [ "\x01\x02", false ],
            [ "\x01\x02\x03", false ],
            [ "\x01\x02\x03\x04", true, unpack("N", "\x01\x02\x03\x04")[1] ],
            [ "\x01\x02\x03\x04", true, null, unpack("N", "\x01\x02\x03\x04")[1] ],
        ];
    }
}
