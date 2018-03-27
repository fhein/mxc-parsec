<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\LittleWordParser;

class LittleWordParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n",
            $parser
        );
    }

    /** @dataProvider littleWordDataProvider */
    public function testLittleWordParser($input, $expectedResult, $expectedValue = null)
    {
        $cfg = $this->getParserConfig(LittleWordParser::class);
        $parser = $this->pm->build(LittleWordParser::class);
        self::assertInstanceOf(LittleWordParser::class, $parser);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue      // expected value
        );
    }

    public function littleWordDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false ],
            [ "\x01\x02", true, unpack('v', "\x01\x02")[1] ]
        ];
    }
}
