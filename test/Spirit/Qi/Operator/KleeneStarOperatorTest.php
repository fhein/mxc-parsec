<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Operator\KleeneStarOperator;
use Mxc\Test\Parsec\Qi\Assets\MockParserNResult;

class KleeneStarOperatorTest extends ParserTestBed
{
    // @todo: Invalid __construct params
    // @todo: Attributes

    protected function getParserConfig(string $parser, array $test)
    {
        return sprintf(
            "Test of %s:\n"
            . "    Operand returns %s for %d times.\n",
            $parser,
            var_export($test[0], true),
            var_export($test[1], true)
        );
    }

    /** @dataProvider kleeneStarOperatorDataProvider */
    public function testKleeneStarOperator(array $test)
    {
        $cfg = $this->getParserConfig(KleeneStarOperator::class, $test);

        $domain = $this->pm->get(Domain::class);
        $mock = new MockParserNResult($domain, $test[1], $test[0]);

        $operator = $this->pm->build(KleeneStarOperator::class, [ $mock ]);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $test[2]                    // expected result
        );
    }

    public function kleeneStarOperatorDataProvider()
    {
        $tests = [
             [[ true, 3, true ]],
             [[ true, 2, true ]],
             [[ true, 1, true ]],
             [[ true, 0, true ]],
             [[ false, 1, true ]],
        ];

        return $tests;
    }
}
