<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\LittleBinDoubleParser;

class LittleBinDoubleParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n",
            $parser
        );
    }

    /** @dataProvider littleBinDoubleDataProvider */
    public function testLittleBinDoubleParser($input, $expectedResult, $expectedValue = null)
    {
        $cfg = $this->getParserConfig(LittleBinDoubleParser::class);
        $parser = $this->pm->build(LittleBinDoubleParser::class);
        self::assertInstanceOf(LittleBinDoubleParser::class, $parser);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue      // expected value
        );
    }

    public function littleBinDoubleDataProvider()
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
            [ "\x01\x02\x03\x04\x05\x06\x07\x08", true, unpack("e", "\x01\x02\x03\x04\x05\x06\x07\x08")[1] ],
        ];
    }
}
