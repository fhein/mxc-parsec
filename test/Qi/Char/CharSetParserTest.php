<?php

namespace Mxc\Test\Parsec\Qi\Char;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Char\CharSetParser;

class CharSetParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, string $charset, bool $negate = false)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    Char Set: %s\n"
            . "    Negate: %s",
            $parser,
            $charset,
            $negate ? 'yes' : 'no'
        );
    }

    /** @dataProvider charSetDataProvider */
    public function testCharSetParser(
        $negate,
        $charset,
        $input,
        $expectedResult,
        $expectedValue = null,
        $expectedAttribute = null
    ) {
        $cfg = $this->getParserConfig(CharSetParser::class, $charset, $negate);
        $parser = $this->pm->build(CharSetParser::class, [ $charset, $negate ]);
        self::assertInstanceOf(CharSetParser::class, $parser);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue,     // expected value
            $expectedAttribute  // expected attribute value
        );
    }

    public function charSetDataProvider()
    {
        return [
            // parser accepts all characters in the set
            [ false, 'abc', '', false ],
            [ false, 'abc', ' ', false ],
            [ false, 'abc', 'a', true, 'a', 'a' ],
            [ false, 'abc', 'a', true, null, 'a' ],
            [ false, 'abc', 'b', true, 'b', 'b' ],
            [ false, 'abc', 'b', true, null, 'b' ],
            [ false, 'abc', 'c', true, 'c', 'c' ],
            [ false, 'abc', 'c', true, null, 'c' ],
            [ false, 'abc', 'A', false ],
            [ false, 'abc', 'B', false ],
            [ false, 'abc', 'C', false ],
            [ false, 'a-z', 'a', true, null, 'a'],
            [ false, 'a-z', 'm', true, null, 'm'],
            [ false, 'a-z', 'z', true, null, 'z'],
            [ false, 'a-z', 'A', false],
            [ false, 'a-z', 'M', false],
            [ false, 'a-z', 'Z', false],
            [ false, 'a-z', '0', false],
            [ false, 'a-z', '%', false],
            [ false, 'a-z', '.', false],
            [ false, 'a-z01', 'a', true, null, 'a'],
            [ false, 'a-z01', 'z', true, null, 'z'],
            [ false, 'a-z01', '-', false ],
            [ false, 'a-z01', '0', true, null, '0' ],
            [ false, 'a-z01', '1', true, null, '1' ],
            [ false, 'a-z01', '2', false ],
            [ false, '-a-z', '-', true, null, '-'],
            [ false, '-a-z', 'a', true, null, 'a'],
            [ false, '-a-z', 'z', true, null, 'z'],
            [ false, '-a-z', '-', false, 'c'],
            [ false, '-a', '-', true, null, '-'],
            [ false, '-a', '-', true, '-', '-'],
            [ false, '-a', '-', false, 'a'],

            // negated parser accepts all characters NOT in the set
            [ true, 'abc', '', false ],
            [ true, 'abc', 'd', true, 'd', 'd'],
            [ true, 'abc', 'a', false ],
            [ true, 'abc', 'b', false ],
            [ true, 'abc', 'c', false ],
            [ true, 'abc', 'A', true, null, 'A' ],
            [ true, 'abc', 'B', true, null, 'B' ],
            [ true, 'abc', 'C', true, null, 'C' ],
            [ true, 'a-z', 'a', false ],
            [ true, 'a-z', 'm', false ],
            [ true, 'a-z', 'z', false ],
            [ true, 'a-z', 'A', true, null, 'A' ],
            [ true, 'a-z', 'M', true, null, 'M' ],
            [ true, 'a-z', 'Z', true, null, 'Z' ],
            [ true, 'a-z', '0', true, null, '0' ],
            [ true, 'a-z', '%', true, null, '%' ],
            [ true, 'a-z', '.', true, null, '.' ],
            [ true, 'a-z01', 'a', false ],
            [ true, 'a-z01', 'z', false ],
            [ true, 'a-z01', '-', true, null, '-' ],
            [ true, 'a-z01', '0', false ],
            [ true, 'a-z01', '1', false ],
            [ true, 'a-z01', '2', true, null, '2' ],
            [ true, '-a-z', '-', false ],
            [ true, '-a-z', 'a', false ],
            [ true, '-a-z', 'z', false ],
            [ true, '-a-z', '-', false],
            [ true, '-a', '-', false ],
            [ true, '-a', '-', false ],
            [ true, '-a', '-', false ],
            [ true, '-a', 'C', true, null, 'C' ],
        ];
    }
}
