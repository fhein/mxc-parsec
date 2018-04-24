<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\BigWordParser;

class BigWordParserTest extends ParserTestBed
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

    /** @dataProvider bigWordDataProvider */
    public function testBigWordParser($input, $expectedResult, $expectedValue = null, $expectedAttribute = null)
    {
        $cfg = $this->getParserConfig(BigWordParser::class, $expectedValue);
        $parser = $this->pm->build(BigWordParser::class, [$expectedValue]);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue,     // expected value
            $expectedAttribute  // expected attribute
        );
    }

    public function bigWordDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false],
            [ "\x01\x02", true, 258 ],
            [ "\x01\x02", true, null, 258 ],
            [ "\x01\x02", false, 42 ],
        ];
    }
}
