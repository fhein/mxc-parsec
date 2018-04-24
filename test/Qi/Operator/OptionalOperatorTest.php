<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Attribute\Optional;
use Mxc\Parsec\Qi\Operator\OptionalOperator;
use Mxc\Test\Parsec\Qi\Assets\MockParserNResult;

class OptionalOperatorTest extends ParserTestBed
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

    /** @dataProvider optionalOperatorDataProvider */
    public function testOptionalOperator(array $test)
    {
        $cfg = $this->getParserConfig(OptionalOperator::class, $test);

        $domain = $this->pm->get(Domain::class);
        $mock = new MockParserNResult($domain, $test[1], $test[0]);

        $operator = $this->pm->build(OptionalOperator::class, [ $mock ]);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $test[2]                    // expected result
        );
    }

    public function optionalOperatorDataProvider()
    {
        $tests = [
             [[ true, 1, true ]],
             [[ false, 1, true ]],
        ];

        return $tests;
    }
}
