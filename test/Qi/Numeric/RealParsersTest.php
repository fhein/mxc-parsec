<?php

namespace Mxc\Test\Parsec\Qi\Numeric;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Numeric\FloatParser;
use Mxc\Parsec\Qi\Numeric\DoubleParser;
use Mxc\Parsec\Qi\Numeric\LongDoubleParser;

class RealParsersTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n",
            $parser
        );
    }

    /** @dataProvider FloatParserDataProvider */
    public function testFloatParser(
        $class,
        $input,
        $expectedResult,
        $expectedValue,
        $expectedAttribute
    ) {
        $cfg = $this->getParserConfig($class);
        $parser = $this->pm->build($class);

        $this->doTest(
            $cfg,
            $parser,
            $input,
            $expectedResult,
            $expectedValue,
            $expectedAttribute
        );
    }

    public function floatParserDataProvider()
    {
        $scenarios = [
            [
                'parsers' => [
                    FloatParser::class,
                    DoubleParser::class,
                    LongDoubleParser::class,
                ],
                'tests' => [
                    ['10', true, 10., 10.0, 'double'],
                    ['10.', true, 10.0, 10.0, 'double'],
                    ['10.0', true, 10.0, 10.0, 'double'],
                    ['-10', true, -10.0, -10.0, 'double'],
                    ['-10.', true, -10.0, -10.0, 'double'],
                    ['-10.0', true, -10.0, -10.0, 'double'],
                    ['10e2', true, 1000.0, 1000.0, 'double'],
                    ['10.e2', true, 1000.0, 1000.0, 'double'],
                    ['10.0e2', true, 1000.0, 1000.0, 'double'],
                    ['-10e2', true, -1000.0, -1000.0, 'double'],
                    ['-10e2', true, -1000.0, -1000.0, 'double'],
                    ['-10.e2', true, -1000.0, -1000.0, 'double'],
                    ['-10.e2', true, -1000.0, -1000.0, 'double'],
                    ['-10e2', true, -1000.0, -1000.0, 'double'],
                    ['NAN', true, NAN, NAN, 'double'],
                    ['-NAN', true, NAN, NAN, 'double'],
                    ['INF', true, INF, INF, 'double'],
                    ['-INF', true, INF, INF, 'double'],
                ],
                'expectedResult' => true,
            ],
        ];

        foreach ($scenarios as $scenario) {
            foreach ($scenario['parsers'] as $parser) {
                foreach ($scenario['tests'] as $test) {
                    $tests[] = [
                        $parser,
                        $test[0],       // input
                        $test[1],       // expected result
                        $test[2],       // expected value
                        $test[3],       // expected attribute
                        $test[4]        // expected attributeType
                    ];
                }
            }
        }
        return $tests;
    }
}
