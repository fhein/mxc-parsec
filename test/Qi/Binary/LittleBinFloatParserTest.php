<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\LittleBinFloatParser;

class LittleBinFloatParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n",
            $parser
        );
    }

    /** @dataProvider littleBinFloatDataProvider */
    public function testLittleBinFloatParser($input, $expectedResult, $expectedValue = null)
    {
        $cfg = $this->getParserConfig(LittleBinFloatParser::class);
        $parser = $this->pm->build(LittleBinFloatParser::class);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue      // expected value
        );
    }

    public function littleBinFloatDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false ],
            [ "\x01\x02", false ],
            [ "\x01\x02\x03", false ],
            [ "\x01\x02\x03\x04", true, unpack("g", "\x01\x02\x03\x04")[1] ],
            [ "\x01\x02\x03\x04", true, null, unpack("g", "\x01\x02\x03\x04")[1] ],
        ];
    }
}
