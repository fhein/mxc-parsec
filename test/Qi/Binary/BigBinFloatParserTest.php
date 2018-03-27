<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\BigBinFloatParser;

class BigBinFloatParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n",
            $parser
        );
    }

    /** @dataProvider bigBinFloatDataProvider */
    public function testBigBinFloatParser($input, $expectedResult, $expectedValue = null)
    {
        $cfg = $this->getParserConfig(BigBinFloatParser::class);
        $parser = $this->pm->build(BigBinFloatParser::class);
        self::assertInstanceOf(BigBinFloatParser::class, $parser);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue      // expected value
        );
    }

    public function bigBinFloatDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false ],
            [ "\x01\x02", false ],
            [ "\x01\x02\x03", false ],
            [ "\x01\x02\x03\x04", true, unpack("G", "\x01\x02\x03\x04")[1] ],
        ];
    }
}
