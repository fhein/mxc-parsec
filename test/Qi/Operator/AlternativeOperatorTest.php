<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Operator\AlternativeOperator;
use Mxc\Test\Parsec\Qi\Assets\MockParserDoParse;

class AlternativeOperatorTest extends ParserTestBed
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

    /** @dataProvider alternativeOperatorDataProvider */
    public function testAlternativeOperator(bool $result1, bool $result2, $result)
    {
        $cfg = $this->getParserConfig(AlternativeOperator::class, $result1, $result2);

        $domain = $this->pm->get(Domain::class);
        $firstMock = new MockParserDoParse($domain, $result1);
        $secondMock = new MockParserDoParse($domain, $result2);

        $operator = $this->pm->build(AlternativeOperator::class, [ [ $firstMock, $secondMock ] ]);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $result                     // expected result
        );
    }

    public function alternativeOperatorDataProvider()
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
