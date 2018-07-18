<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\LittleWordParser;

class LittleWordParserTest extends ParserTestBed
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

    /** @dataProvider littleWordDataProvider */
    public function testLittleWordParser($input, $expectedResult, $expectedValue = null, $expectedAttribute = null)
    {
        $cfg = $this->getParserConfig(LittleWordParser::class, $expectedValue);
        $uid = 'test';

        $parser = $this->pm->build(LittleWordParser::class, [$uid, $expectedValue]);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue,     // expected value
            $expectedAttribute  // expected attribute
        );
    }

    public function littleWordDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false ],
            [ "\x01\x02", true, unpack('v', "\x01\x02")[1], unpack('v', "\x01\x02")[1] ],
            [ "\x01\x02", true, null, unpack('v', "\x01\x02")[1] ],
            [ "\x01\x02", false, 42, unpack('v', "\x01\x02")[1] ],
        ];
    }
}
