<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Operator\PermutationOperator;
use Mxc\Test\Parsec\Qi\Assets\MockParserDoParse;

class PermutationOperatorTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, bool $result1, bool $result2)
    {

        return sprintf(
            "Test of %s:\n"
            . "    Operand 1 Result: %s\n"
            . "    Operand 2 Result: %s\n",
            $parser,
            var_export($result1, true),
            var_export($result2, true)
        );
    }

    /** @dataProvider permutationOperatorDataProvider */
    public function testPermutationOperator(bool $result1, bool $result2, $result)
    {
        $cfg = $this->getParserConfig(PermutationOperator::class, $result1, $result2);

        $domain = $this->pm->get(Domain::class);
        $firstMock = new MockParserDoParse($domain, $result1);
        $secondMock = new MockParserDoParse($domain, $result2);
        $uid = 'test';

        $operator = $this->pm->build(PermutationOperator::class, [ $uid, [ $firstMock, $secondMock ] ]);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $result                     // expected result
        );
    }

    public function permutationOperatorDataProvider()
    {
        $tests = [
            [ true, true, true ],
            [ true, false, true ],
            [ false, true, true ],
            [ false, false, false],
        ];

        return $tests;
    }
}
