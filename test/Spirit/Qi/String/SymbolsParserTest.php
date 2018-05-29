<?php

namespace Mxc\Test\Parsec\Qi\String;

use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Test\Parsec\ParserTestBed;

class SymbolsParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, $symbols)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    Symbols: %s\n",
            $parser,
            var_export($symbols, true)
        );
    }

    /** @dataProvider symbolsParserDataProvider */
    public function testSymbolsParserConstructor(
        $setup,
        $input,
        $expectedValue,
        $expectedResult,
        $expectedAttribute = null,
        $expectedIteratorPos = null
    ) {
        $cfg = $this->getParserConfig(SymbolsParser::class, $setup);
        $parser = $this->pm->build(SymbolsParser::class, [ $setup ]);

        $this->doTest(
            $cfg,
            $parser,
            $input,
            $expectedResult,
            $expectedValue,
            $expectedAttribute,
            null,
            $expectedIteratorPos
        );
    }

    /** @dataProvider symbolsParserDataProvider */
    public function testSymbolsParserAdd(
        $setup,
        $input,
        $expectedValue,
        $expectedResult,
        $expectedAttribute = null,
        $expectedIteratorPos = null
    ) {
        $cfg = $this->getParserConfig(SymbolsParser::class, $setup);
        $parser = $this->pm->build(SymbolsParser::class);

        foreach ($setup as $symbol => $value) {
            $parser->add($symbol, $value);
        }

        $this->doTest(
            $cfg,
            $parser,
            $input,
            $expectedResult,
            $expectedValue,
            $expectedAttribute,
            null,
            $expectedIteratorPos
        );
    }

    public function symbolsParserDataProvider()
    {
        $tests = [
            [[ 'abc' => 1, 'DEF' => 2 ], 'abc', 1, true, 1],
            [[ 'abc' => 1, 'DEF' => 2 ], 'abc', null, true, 1],
            [[ 'abc' => 1, 'DEF' => 2 ], 'ABC', null, false],
            [[ 'abc' => 1, 'DEF' => 2 ], 'def', null, false],
            [[ 'abc' => 1, 'DEF' => 2 ], 'DEF', 2, true, 2],
            [[ 'abc' => 1, 'DEF' => 2 ], 'DEF', null, true, 2],
            [[ 'abc' => 1, 'DEF' => 2 ], 'xyz', null, false],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABC', null, true, 1],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCDEF', null, true, 2, 6],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCDEFGHI', null, true, 3, 9],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCD', null, true, 1, 3],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCDEFG', null, true, 2, 6],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCxyz', null, true, 1, 3],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCDEFxyz', null, true, 2, 6],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABC', 1, true, 1, 3],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCDEF', 2, true, 2, 6],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCDEFGHI', 3, true, 3, 9],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCD', 1, true, 1, 3],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCDEFG', 2, true, 2, 6],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCxyz', 1, true, 1, 3],
            [['ABC' => 1, 'ABCDEF' => 2, 'ABCDEFGHI' => 3], 'ABCDEFxyz', 2, true, 2, 6],
        ];

        return $tests;
    }
}
