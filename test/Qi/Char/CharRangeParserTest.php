<?php

namespace Mxc\Test\Parsec\Qi\Char;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Char\CharRangeParser;

class CharRangeParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, string $min, string $max, bool $negate = false)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    Min: %s\n"
            . "    Max: %s\n"
            . "    Negate: %s\n",
            $parser,
            var_export($min, true),
            var_export($max, true),
            $negate ? 'yes' : 'no'
        );
    }

    /** @dataProvider charRangeDataProvider */
    public function testCharRangeParser(
        $negate,
        $min,
        $max,
        $input,
        $expectedResult,
        $expectedValue = null,
        $expectedAttribute = null
    ) {
        $cfg = $this->getParserConfig(CharRangeParser::class, $min, $max, $negate);
        $parser = $this->pm->build(CharRangeParser::class, [ $min, $max, $negate ]);
        self::assertInstanceOf(CharRangeParser::class, $parser);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue,     // expected value
            $expectedAttribute  // expected attribute value
        );
    }

    public function charRangeDataProvider()
    {
        return [
            // parser accepts all characters in the set
            [ false, 'a', 'z',  'a', true, 'a', 'a' ],
            [ false, 'a', 'z',  'a', true, null, 'a' ],
            [ false, 'a', 'z',  'd', true, 'd', 'd' ],
            [ false, 'a', 'z',  'd', false, 'e' ],
            [ false, 'a', 'z',  'd', true, null, 'd' ],
            [ false, 'a', 'z',  'z', true, 'z', 'z' ],
            [ false, 'a', 'z',  'z', true, null, 'z' ],
            [ false, 'a', 'z',  'A', false ],
            [ false, 'a', 'z',  'Z', false ],
            [ false, 'a', 'z',  '[', false ],

            // negated parser accepts all characters NOT in the set
            [ true, 'a', 'z',  'a', false ],
            [ true, 'a', 'z',  'a', false ],
            [ true, 'a', 'z',  'd', false ],
            [ true, 'a', 'z',  'z', false ],
            [ true, 'a', 'z',  'A', true, 'A', 'A' ],
            [ true, 'a', 'z',  'A', true, null, 'A' ],
            [ true, 'a', 'z',  'Z', true, null, 'Z' ],
            [ true, 'a', 'z',  'Z', true, 'Z', 'Z' ],
            [ true, 'a', 'z',  '[', true, null, '[' ],
            [ true, 'a', 'z',  '[', true, '[', '[' ],
            [ true, 'a', 'z',  '[', false, ']' ],
        ];
    }
}
