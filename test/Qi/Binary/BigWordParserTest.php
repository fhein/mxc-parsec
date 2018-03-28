<?php

namespace Mxc\Test\Parsec\Qi\Binary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Binary\BigWordParser;

class BigWordParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n",
            $parser
        );
    }

    /** @dataProvider bigWordDataProvider */
    public function testBigWordParser($input, $expectedResult, $expectedValue = null)
    {
        $cfg = $this->getParserConfig(BigWordParser::class);
        $parser = $this->pm->build(BigWordParser::class);
        self::assertInstanceOf(BigWordParser::class, $parser);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue      // expectedValue
        );
    }

    public function bigWordDataProvider()
    {
        return [
            [ '', false ],
            [ "\x01", false],
            [ "\x01\x02", true, 258],
            [ "\x01\x02", true, null, 258],
        ];
    }
}
