<?php

namespace Mxc\Test\Parsec\Qi\Operator;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Operator\AlternativeOperator;
use Mxc\Parsec\Qi\PreSkipper;
use Mxc\Parsec\Qi\PrimitiveParser;

class AlternativeOperatorTest extends ParserTestBed
{
    protected function getParserConfig(string $operator, $mocks)
    {
        $first = $mocks['firstMock'];
        $second = $mocks['secondMock'];

        return sprintf(
            "Test of %s:\n"
            . "    Operand 1 Mock Base: %s\n"
            . "    Operand 1 Method: %s\n"
            . "    Operand 1 Result: %s\n"
            . "    Operand 2 Mock Base: %s\n"
            . "    Operand 2 Method: %s\n"
            . "    Operand 2 Result: %s\n",
            $operator,
            $first[0],
            $first[1],
            var_export($first[2], true),
            $second[0],
            $second[1],
            var_export($second[2], true)
        );
    }

    protected function makeMock($base, $method, $result)
    {
        $mock = $this->getMockBuilder($base)
        ->setMethods([$method])
        ->setConstructorArgs([ $this->pm->get(Domain::class)])
        ->getMock();

        $mock->expects($this->any())
        ->method($method)
        ->willReturn($result);

        return $mock;
    }

    /** @dataProvider alternativeOperatorDataProvider */
    public function testAlternativeOperator(
        array $mocks
    ) {
        $cfg = $this->getParserConfig(
            AlternativeOperator::class,
            $mocks
        );

        foreach ($mocks as $mock => $config) {
            if (is_array($config)) {
                $$mock = $this->makeMock($config[0], $config[1], $config[2]);
            }
        }

        $operator = $this->pm->build(AlternativeOperator::class, [ [ $firstMock, $secondMock ] ]);
        self::assertInstanceOf(AlternativeOperator::class, $operator);

        $this->doTest(
            $cfg,                       // test configuration description
            $operator,                  // operator to test
            '',                         // input
            $mocks['expectedResult']    // expected result
        );
    }

    public function alternativeOperatorDataProvider()
    {
        $mockBases = [
            PrimitiveParser::class,
            PreSkipper::class,
        ];

        $results = [
            [ true, true, true ],
            [ true, false, true ],
            [ false, true, true ],
            [ false, false, false],
        ];

        $count = count($mockBases);
        foreach ($mockBases as $firstMock) {
            foreach ($mockBases as $secondMock) {
                foreach ($results as $result) {
                    $tests[] = [
                        [
                            'firstMock' => [
                                $firstMock,
                                'doParse',
                                $result[0],
                            ],
                            'secondMock' => [
                                $secondMock,
                                'doParse',
                                $result[1],
                            ],
                            'expectedResult' => $result[2],
                        ]
                    ];
                }
            }
        }

        return $tests;
    }
}
