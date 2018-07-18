<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Test\Parsec\Qi\Assets\MockParserDoParse;

class DifferenceOperatorTest extends ParserTestBed
{
    // @todo: Invalid __construct params
    // @todo: Attributes

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

    /** @dataProvider differenceOperatorDataProvider */
    public function testDifferenceOperator(bool $result1, bool $result2, $result)
    {
        $cfg = $this->getParserConfig(DifferenceOperator::class, $result1, $result2);

        $domain = $this->pm->get(Domain::class);
        $firstMock = new MockParserDoParse($domain, $result1);
        $secondMock = new MockParserDoParse($domain, $result2);
        $uid = 'test';

        $operator = $this->pm->build(DifferenceOperator::class, [ $uid, $firstMock, $secondMock ]);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $result                     // expected result
        );
    }

    public function differenceOperatorDataProvider()
    {
        $tests = [
            [ true, true, false ],
            [ true, false, true ],
            [ false, true, false ],
            [ false, false, false],
        ];

        return $tests;
    }
}
