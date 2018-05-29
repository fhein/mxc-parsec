<?php

namespace Mxc\Test\Parsec\Qi\Char;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Char\CharParser;

class CharParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, string $char = null, bool $negate = false)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    Char: %s\n"
            . "    Negate: %s\n",
            $parser,
            $char ?? 'all',
            $negate ? 'yes' : 'no'
        );
    }

    /** @dataProvider charDataProvider */
    public function testCharParser(
        $negate,
        $char,
        $input,
        $expectedResult,
        $expectedValue = null,
        $expectedAttribute = null
    ) {
        $cfg = $this->getParserConfig(CharParser::class, $char, $negate);
        $parser = $this->pm->build(CharParser::class, [ $char, $negate ]);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue,     // expected value
            $expectedAttribute  // expected attribute value
        );
    }

    public function charDataProvider()
    {
        $tests = [
            [ false, 'A', 'A', true, null, 'A'],
            [ false, 'A', 'A', true, 'A', 'A'],
            [ false, 'B', 'A', false, 'B'],
            [ false, 'B', 'b', false, 'B'],
            [ true, 'A', 'A', false, null ],
            [ true, 'A', 'B', true, 'B', 'B' ],
            [ true, 'A', 'B', true, null, 'B' ],
        ];
        return $tests;
    }
}
