<?php

namespace Mxc\Test\Parsec\Qi\Numeric;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Numeric\BoolParser;
use Mxc\Parsec\Qi\Numeric\Detail\BoolPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\NoCaseBoolPolicy;
use Mxc\Test\Parsec\Qi\Numeric\Assets\BackwardsBoolPolicy;

class BoolParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, $boolPolicy)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    Policy: %s\n",
            $parser,
            $boolPolicy
        );
    }

    /** @dataProvider boolParserDataProvider */
    public function testBoolParser(
        $policy,
        $input,
        $expectedValue,
        $expectedResult,
        $expectedAttribute
    ) {
        $cfg = $this->getParserConfig(BoolParser::class, $policy);
        $parser = $this->pm->build(BoolParser::class, [ new $policy() ]);

        $this->doTest(
            $cfg,
            $parser,
            $input,
            $expectedResult,
            $expectedValue,
            $expectedAttribute
        );
    }

    public function boolParserDataProvider()
    {
        $scenarios = [
            [
                'policies' => [
                    BoolPolicy::class,
                    NoCaseBoolPolicy::class,
                    BackwardsBoolPolicy::class,
                ],
                'input' => [
                    'FaLsE',
                    'tRuE',
                    'T',
                    'F',
                    't',
                    'f',
                    '',
                    ' ',
                    'eUrT',
                    'eSlAf'
                ],
                'expectedValue' => [ true, false ],
                'expectedResult' => false,
            ],
            [
                'policies' => [
                    BoolPolicy::class,
                    NoCaseBoolPolicy::class,
                    BackwardsBoolPolicy::class,
                ],
                'input' => [
                    'true',
                ],
                'expectedValue' => [ null, true ],
                'expectedResult' => true,
                'expectedAttribute' => true,
            ],
            [
                'policies' => [
                    NoCaseBoolPolicy::class,
                    BackwardsBoolPolicy::class
                ],
                'input' => [
                    'TRUE',
                ],
                'expectedValue' => [ null, true ],
                'expectedResult' => true,
                'expectedAttribute' => true,
            ],
            [
                'policies' => [
                    BoolPolicy::class,
                    NoCaseBoolPolicy::class,
                ],
                'input' => [
                    'false',
                ],
                'expectedValue' => [ null, false ],
                'expectedResult' => true,
                'expectedAttribute' => false,
            ],
            [
                'policies' => [
                    BackwardsBoolPolicy::class,
                ],
                'input' => [
                    'false',
                    'FALSE'
                ],
                'expectedValue' => [ null, false, true ],
                'expectedResult' => false,
            ],
            [
                'policies' => [
                    NoCaseBoolPolicy::class,
                ],
                'input' => [
                    'FALSE',
                ],
                'expectedValue' => [ null, false ],
                'expectedResult' => true,
                'expectedAttribute' => false,
            ],
            [
                'policies' => [
                    BackwardsBoolPolicy::class,
                ],
                'input' => [
                    'eurt',
                    'EURT'
                ],
                'expectedValue' => [ null, false ],
                'expectedResult' => true,
                'expectedAttribute' => false,
            ],
        ];

        foreach ($scenarios as $scenario) {
            $inputs = $scenario['input'];
            $policies = $scenario['policies'];
            $expectedValues = $scenario['expectedValue'];
            $expectedResult = $scenario['expectedResult'];
            $expectedAttribute = $scenario['expectedAttribute'] ?? null;
            foreach ($policies as $policy) {
                foreach ($inputs as $input) {
                    foreach ($expectedValues as $expectedValue) {
                        // verify that parser fails
                        $tests[] = [
                            $policy,                // boolean policy to use
                            $input,                 // string to parse
                            $expectedValue,         // acceptable value or null for any
                            $expectedResult,        // expected result of parse (true/false)
                            $expectedAttribute      // expected typed attribute
                        ];
                    }
                }
            }
        }
        return $tests;
    }
}
