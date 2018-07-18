<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Operator\AndPredicate;
use Mxc\Test\Parsec\Qi\Assets\MockParserNResult;
use Mxc\Test\Parsec\Qi\Assets\MockParserDoParse;

class AndPredicateTest extends ParserTestBed
{
    // @todo: Invalid __construct params
    // @todo: Attributes

    protected function getParserConfig(string $parser, array $test)
    {
        return sprintf(
            "Test of %s:\n"
            . "    Operand returns %s.\n"
            . "    Expected iterator pos: %d\n",
            $parser,
            var_export($test[0], true),
            var_export($test[2], true)
        );
    }

    /** @dataProvider andPredicateDataProvider */
    public function testAndPredicate(array $test)
    {
        $cfg = $this->getParserConfig(AndPredicate::class, $test);

        $domain = $this->pm->get(Domain::class);
        $mock = new MockParserDoParse($domain, $test[0]);
        $uid = 'test';

        $operator = $this->pm->build(AndPredicate::class, [ $uid, $mock ]);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $test[1]                    // expected result
        );
    }

    public function andPredicateDataProvider()
    {
        $tests = [
             [[ true, true, 4 ]],
             [[ false, false, 2 ]],
        ];

        return $tests;
    }
}
