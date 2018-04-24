<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\WordParser;

class WordParserTest extends ParserTestBed
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

    /** @dataProvider wordDataProvider */
    public function testWordParser($input, $expectedResult, $expectedValue = null, $expectedAttribute = null)
    {
        $cfg = $this->getParserConfig(WordParser::class, $expectedValue);
        $parser = $this->pm->build(WordParser::class, [$expectedValue]);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue,     // expected value
            $expectedAttribute  // expected attribute
        );
    }

    public function wordDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false ],
            [ "\x01\x02", true, unpack('S', "\x01\x02")[1], unpack('S', "\x01\x02")[1] ],
            [ "\x01\x02", true, null, unpack('S', "\x01\x02")[1] ],
            [ "\x01\x02", false, 42 ],
        ];
    }
}
