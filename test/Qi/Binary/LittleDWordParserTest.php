<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\LittleDWordParser;

class LittleDWordParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n",
            $parser
        );
    }

    /** @dataProvider littleDWordDataProvider */
    public function testLittleDWordParser($input, $expectedResult, $expectedValue = null)
    {
        $cfg = $this->getParserConfig(LittleDWordParser::class);
        $parser = $this->pm->build(LittleDWordParser::class);
        self::assertInstanceOf(LittleDWordParser::class, $parser);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue      // expected value
        );
    }

    public function littleDWordDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false ],
            [ "\x01\x02", false ],
            [ "\x01\x02\x03", false ],
            [ "\x01\x02\x03\x04", true, unpack("V", "\x01\x02\x03\x04")[1] ],
            [ "\x01\x02\x03\x04", true, null, unpack("V", "\x01\x02\x03\x04")[1] ],
        ];
    }
}
