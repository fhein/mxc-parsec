<?php

namespace Mxc\Test\Parsec\Qi\String;

use Mxc\Parsec\Qi\String\StringParser;
use Mxc\Test\Parsec\ParserTestBed;

class StringParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, $expectedValue)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    String: %s\n",
            $parser,
            $expectedValue
        );
    }

    /** @dataProvider stringParserDataProvider */
    public function testStringParser($input, $expectedValue, $expectedResult, $expectedAttribute = null)
    {
        $cfg = $this->getParserConfig(StringParser::class, $expectedValue);
        $uid = 'test';

        $parser = $this->pm->build(StringParser::class, [$uid, $expectedValue]);

        $this->doTest($cfg, $parser, $input, $expectedResult, $expectedValue, $expectedAttribute);
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
}
