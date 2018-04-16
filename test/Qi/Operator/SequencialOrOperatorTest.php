<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Operator\SequentialOrOperator;
use Mxc\Parsec\Qi\Char\CharParser;

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
    public function testSequentialOrOperator(string $input, $expectedResult, $expectedAttribute = null)
    {
        $cfg = $this->getParserConfig(SequentialOrOperator::class);

        for ($i = 1; $i < 6; $i++) {
            $p[$i] = $this->pm->build(CharParser::class, [strval($i)]);
        }

        $operator = $this->pm->build(SequentialOrOperator::class, [[ $p[1], $p[2], $p[3], $p[4], $p[5]]]);
        self::assertInstanceOf(SequentialOrOperator::class, $operator);

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
            [ '1 2 3 4 5 6',    true,   ['1', '2', '3', '4', '5'] ],
            [ '1 2 5 6',        true,   ['1', '2', '5'] ],
            [ '2 3 4 5 6',      true,   ['2', '3', '4', '5'] ],
            [ '1 3 4 5 6',      true,   ['1', '3', '4', '5'] ],
            [ '3 4 5 6 7',      false ],
            [ '2 4 5 6 7',      true,   [ '2', '4', '5'] ],
            [ '1 5',            true,   ['1', '5'] ],
            [ '4 5',            false ],
            [ '1',              true,   [ '1'] ],
            [ '2',              true,   [ '2'] ],
            [ '3',              false ],
            [ '1 2 4 3 5 6',    true,   ['1', '2', '4'] ],
            [ '1 5 3 4',        true,   [ '1', '5'] ],
            [ '0 1 2 3',        false ]
        ];

        return $tests;
    }
}
