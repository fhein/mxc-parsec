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
        $expectedAttribute = null
    ) {
        $cfg = $this->getParserConfig(CharRangeParser::class, $min, $max, $negate);
        $parser = $this->pm->build(CharRangeParser::class, [ $min, $max, $negate ]);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            null,               // expected value (not applicable)
            $expectedAttribute  // expected attribute
        );
    }

    public function charRangeDataProvider()
    {
        return [
            // parser accepts all characters in the set
            [ false, 'a', 'z',  'a', true],
            [ false, 'a', 'z',  'd', true, 'd' ],
            [ false, 'a', 'z',  'z', true, 'z', 'z' ],
            [ false, 'a', 'z',  'z', true ],
            [ false, 'a', 'z',  'A', false ],
            [ false, 'a', 'z',  'Z', false ],
            [ false, 'a', 'z',  '[', false ],

            // negated parser accepts all characters NOT in the set
            [ true, 'a', 'z',  'a', false ],
            [ true, 'a', 'z',  'a', false ],
            [ true, 'a', 'z',  'd', false ],
            [ true, 'a', 'z',  'z', false ],
            [ true, 'a', 'z',  'A', true, 'A' ],
            [ true, 'a', 'z',  'Z', true, 'Z' ],
            [ true, 'a', 'z',  '[', true, '[' ],
        ];
    }
}
