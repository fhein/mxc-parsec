<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Operator\SequenceOperator;
use Mxc\Test\Parsec\Qi\Assets\MockParserDoParse;

class SequenceOperatorTest extends ParserTestBed
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

    /** @dataProvider sequenceOperatorDataProvider */
    public function testSequenceOperator(bool $result1, bool $result2, $result)
    {
        $cfg = $this->getParserConfig(SequenceOperator::class, $result1, $result2);

        $domain = $this->pm->get(Domain::class);
        $firstMock = new MockParserDoParse($domain, $result1);
        $secondMock = new MockParserDoParse($domain, $result2);

        $operator = $this->pm->build(SequenceOperator::class, [ [ $firstMock, $secondMock ] ]);
        self::assertInstanceOf(SequenceOperator::class, $operator);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $result                     // expected result
        );
    }

    public function sequenceOperatorDataProvider()
    {
        $tests = [
            [ true, true, true ],
            [ true, false, false ],
            [ false, true, false ],
            [ false, false, false],
        ];

        return $tests;
    }
}
