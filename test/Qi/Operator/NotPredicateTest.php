<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Operator\NotPredicate;
use Mxc\Test\Parsec\Qi\Assets\MockParserDoParse;

class NotPredicateTest extends ParserTestBed
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

    /** @dataProvider notPredicateDataProvider */
    public function testNotPredicate(array $test)
    {
        $cfg = $this->getParserConfig(NotPredicate::class, $test);

        $domain = $this->pm->get(Domain::class);
        $mock = new MockParserDoParse($domain, $test[0]);

        $operator = $this->pm->build(NotPredicate::class, [ $mock ]);
        self::assertInstanceOf(NotPredicate::class, $operator);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $test[1]                    // expected result
        );
    }

    public function notPredicateDataProvider()
    {
        $tests = [
             [[ true, false, 4 ]],
             [[ false, true, 2 ]],
        ];

        return $tests;
    }
}
