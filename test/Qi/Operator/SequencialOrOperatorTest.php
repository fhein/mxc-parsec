<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Operator\SequentialOrOperator;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Attribute\Optional;
use Mxc\Parsec\Attribute\Unused;

class SequentialOrOperatorTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n",
            "  Parsers: CharParser('1'), ..., CharParser('5)\n",
            $parser
        );
    }

    /** @dataProvider SequentialOrOperatorDataProvider */
    public function testSequentialOrOperator(string $input, $expectedResult, $attributes = null)
    {
        $cfg = $this->getParserConfig(SequentialOrOperator::class);

        $unused = new Unused();
        for ($i = 0; $i < 5; $i++) {
            $p[$i] = $this->pm->build(CharParser::class, [strval($i + 1)]);
            $expectedAttribute[$i] = new Optional($attributes[$i] ?? $unused);
        }

        $operator = $this->pm->build(SequentialOrOperator::class, [[ $p[0], $p[1], $p[2], $p[3], $p[4]]]);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            $input,                     // input
            $expectedResult,            // expected result
            null,                       // expected value
            $expectedAttribute          // expectedAttribute
        );
    }

    public function sequentialOrOperatorDataProvider()
    {
        $tests = [
            // input            result  expected attribute
            [ '1 2 3 4 5 6',    true,   [ 0 => '1', 1 => '2', 2 => '3', 3 => '4', 4 => '5'] ],
            [ '1 2 5 6',        true,   [ 0 => '1', 1 => '2', 4 => '5'] ],
            [ '2 3 4 5 6',      true,   [ 1 => '2', 2 => '3', 3 => '4', 4 => '5'] ],
            [ '1 3 4 5 6',      true,   [ 0 => '1', 2 => '3', 3 => '4', 4 => '5'] ],
            [ '3 4 5 6 7',      false ],
            [ '2 4 5 6 7',      true,   [ 1 => '2', 3 => '4', 4 => '5'] ],
            [ '1 5',            true,   [ 0 => '1', 4 => '5'] ],
            [ '4 5',            false ],
            [ '1',              true,   [ 0 => '1'] ],
            [ '2',              true,   [ 1 => '2'] ],
            [ '3',              false ],
            [ '1 2 4 3 5 6',    true,   [ 0 => '1', 1 => '2', 3 => '4'] ],
            [ '1 5 3 4',        true,   [ 0 => '1', 4 => '5'] ],
            [ '0 1 2 3',        false ]
        ];

        return $tests;
    }
}
