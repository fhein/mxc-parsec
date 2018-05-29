<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\BinDoubleParser;

class BinDoubleParserTest extends ParserTestBed
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

    /** @dataProvider binDoubleDataProvider */
    public function testBinDoubleParser($input, $expectedResult, $expectedValue = null, $expectedAttribute = null)
    {
        $cfg = $this->getParserConfig(BinDoubleParser::class, $expectedValue);
        $parser = $this->pm->build(BinDoubleParser::class, [$expectedValue]);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue,     // expected value
            $expectedAttribute  // expected attribute
        );
    }

    public function binDoubleDataProvider()
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
            [ "\x01\x02\x03\x04\x05\x06\x07\x08", true, unpack("d", "\x01\x02\x03\x04\x05\x06\x07\x08")[1], unpack("d", "\x01\x02\x03\x04\x05\x06\x07\x08")[1] ],
            [ "\x01\x02\x03\x04\x05\x06\x07\x08", true, null, unpack("d", "\x01\x02\x03\x04\x05\x06\x07\x08")[1] ],
            [ "\x01\x02\x03\x04\x05\x06\x07\x08", false, 42.0 ],
        ];
    }
}
