<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Operator\ListOperator;
use Mxc\Test\Parsec\Qi\Assets\MockParserNResult;

class ListOperatorTest extends ParserTestBed
{
    // @todo: Invalid __construct params
    // @todo: Attributes

    protected function getParserConfig(string $parser, array $test)
    {
        return sprintf(
            "Test of %s:\n"
            . "    Operand 1 returns %s for %d times.\n"
            . "    Operand 2 returns %s for %d times.\n",
            $parser,
            var_export($test[0], true),
            var_export($test[1], true),
            var_export($test[2], true),
            var_export($test[3], true)
        );
    }

    /** @dataProvider listOperatorDataProvider */
    public function testListOperator(array $test)
    {
        $cfg = $this->getParserConfig(ListOperator::class, $test);

        $domain = $this->pm->get(Domain::class);
        $firstMock = new MockParserNResult($domain, $test[1], $test[0]);
        $secondMock = new MockParserNResult($domain, $test[3], $test[2]);

        $operator = $this->pm->build(ListOperator::class, [ $firstMock, $secondMock ]);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $test[4]                    // expected result
        );
    }

    public function listOperatorDataProvider()
    {
        $tests = [
            [[ true, 3,  true, 3, true ]],
            [[ true, 1, false, 1, true ]],
            [[ false, 1, true, 1, false ]],
            [[ false, 1, false, 1, false ]],
        ];

        return $tests;
    }
}
