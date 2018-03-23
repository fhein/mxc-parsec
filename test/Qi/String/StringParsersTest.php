<?php

namespace Mxc\Test\Parsec\Qi\String;

use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Qi\String\StringParser;
use Mxc\Test\Parsec\ParserTestBed;

class StringParsersTest extends ParserTestBed
{
    /** @dataProvider symbolsParserDataProvider */
    public function testSymbolsParserConstructor($input, $expectedValue, $expectedResult, $expectedAttribute = null)
    {
        $parser = $this->pm->build(SymbolsParser::class, [[ 'abc' => 1, 'DEF' => 2 ]]);
        self::assertInstanceOf(SymbolsParser::class, $parser);
        $this->doTest($parser, $input, $expectedResult, $expectedValue, $expectedAttribute);
    }

    /** @dataProvider symbolsParserDataProvider */
    public function testSymbolsParserAdd($input, $expectedValue, $expectedResult, $expectedAttribute = null)
    {
        $parser = $this->pm->build(SymbolsParser::class);
        self::assertInstanceOf(SymbolsParser::class, $parser);
        $parser->add('abc', 1);
        $parser->add('DEF', 2);
        $this->doTest($parser, $input, $expectedResult, $expectedValue, $expectedAttribute);
    }

    /** @dataProvider stringParserDataProvider */
    public function testStringParser($input, $expectedValue, $expectedResult, $expectedAttribute = null)
    {
        $parser = $this->pm->build(StringParser::class, [$expectedValue]);
        self::assertInstanceOf(StringParser::class, $parser);

        $this->doTest($parser, $input, $expectedResult, $expectedValue, $expectedAttribute);
    }

    public function stringParserDataProvider()
    {
        $tests = [
            ['abc', 'a', true, 'a' ],
            ['abc', 'ab', true, 'ab' ],
            ['abc', 'abc', true, 'abc' ],
            ['abc', 'abcd', false],
            ['abc', 'w', false],
            ['abc', 'wx', false],
            ['abc', 'wxy', false],
            ['abc', 'wxyz', false],
            ['a bc', 'a', true, 'a'],
            ['a bc', 'ab', false],
            ['a bc', 'abc', false],
            ['a bc', 'abcd', false],
            ['a bc', 'a bc', true, 'a bc'],
        ];
        return $tests;
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
