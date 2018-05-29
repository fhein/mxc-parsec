<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\BigDWordParser;

class BigDWordParserTest extends ParserTestBed
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

    /** @dataProvider bigDWordDataProvider */
    public function testbigDWordParser($input, $expectedResult, $expectedValue = null, $expectedAttribute = null)
    {
        $cfg = $this->getParserConfig(BigDWordParser::class, $expectedValue);
        $parser = $this->pm->build(BigDWordParser::class, [$expectedValue]);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue,     // expected value
            $expectedAttribute  // expected attribute
        );
    }

    public function bigDWordDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false ],
            [ "\x01\x02", false ],
            [ "\x01\x02\x03", false ],
            [ "\x01\x02\x03\x04", true, unpack("N", "\x01\x02\x03\x04")[1], unpack("N", "\x01\x02\x03\x04")[1] ],
            [ "\x01\x02\x03\x04", true, null, unpack("N", "\x01\x02\x03\x04")[1] ],
            [ "\x01\x02\x03\x04", false, 42 ],
        ];
    }
}
