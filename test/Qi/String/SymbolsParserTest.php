<?php

namespace Mxc\Test\Parsec\Qi\String;

use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Qi\String\StringParser;
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
    public function testSymbolsParserConstructor($input, $expectedValue, $expectedResult, $expectedAttribute = null)
    {
        $setup = [ 'abc' => 1, 'DEF' => 2 ];
        $cfg = $this->getParserConfig(SymbolsParser::class, $setup);
        $parser = $this->pm->build(SymbolsParser::class, [ $setup ]);
        self::assertInstanceOf(SymbolsParser::class, $parser);
        $this->doTest($cfg, $parser, $input, $expectedResult, $expectedValue, $expectedAttribute);
    }

    /** @dataProvider symbolsParserDataProvider */
    public function testSymbolsParserAdd($input, $expectedValue, $expectedResult, $expectedAttribute = null)
    {
        $setup = [ ];
        $cfg = $this->getParserConfig(SymbolsParser::class, $setup);
        $parser = $this->pm->build(SymbolsParser::class);
        self::assertInstanceOf(SymbolsParser::class, $parser);
        $parser->add('abc', 1);
        $parser->add('DEF', 2);
        $cfg .= "    Added: ('abc', 1), ('DEF', 2)\n";
        $this->doTest($cfg, $parser, $input, $expectedResult, $expectedValue, $expectedAttribute);
    }

    public function symbolsParserDataProvider()
    {
        $tests = [
            ['abc', 1, true, 1],
            ['abc', 2, false],
            ['abc', null, true, 1],
            ['ABC', null, false],
            ['def', null, false],
            ['DEF', 1, false],
            ['DEF', 2, true, 2],
            ['DEF', null, true, 2],
            ['xyz', null, false],
        ];

        return $tests;
    }
}
