<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Operator\ExpectOperator;
use Mxc\Parsec\Qi\Directive\Detail\ExpectationFailedException;
use Mxc\Test\Parsec\Qi\Assets\MockParserDoParse;

class ExpectOperatorTest extends ParserTestBed
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

    /** @dataProvider expectOperatorDataProvider */
    public function testExpectOperator(bool $result1, bool $result2, $result)
    {
        $cfg = $this->getParserConfig(ExpectOperator::class, $result1, $result2);

        $domain = $this->pm->get(Domain::class);
        $firstMock = new MockParserDoParse($domain, $result1);
        $secondMock = new MockParserDoParse($domain, $result2);

        $operator = $this->pm->build(ExpectOperator::class, [ [ $firstMock, $secondMock ] ]);

        if (is_string($result)) {
            self::expectException(ExpectationFailedException::class);
            $result = false;
        }

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $result                     // expected result
        );
    }

    public function expectOperatorDataProvider()
    {
        $tests = [
            [ true, true, true ],
            [ true, false, ExpectationFailedException::class ],
            [ false, true, false ],
            [ false, false, false],
        ];

        return $tests;
    }
}
