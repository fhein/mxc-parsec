<?php

namespace Mxc\Test\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Numeric\BinaryParser;
use Mxc\Parsec\Qi\Numeric\HexParser;
use Mxc\Parsec\Qi\Numeric\IntParser;
use Mxc\Parsec\Qi\Numeric\OctParser;
use Mxc\Parsec\Qi\Numeric\UIntParser;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Numeric\LongParser;
use Mxc\Parsec\Qi\Numeric\ULongParser;
use Mxc\Parsec\Qi\Numeric\LongLongParser;
use Mxc\Parsec\Qi\Numeric\ULongLongParser;

class IntegerParsersTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, array $configuration)
    {
        $value = $configuration[0] ?? 'all';
        $minDigits = $configuration[1] ?? 1;
        $maxDigits = $configuration[2] ?? 0;
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    Accept Value: %s\n"
            . "    Min Digits: %d\n"
            . "    Max Digits: %d\n",
            $parser,
            $value,
            $minDigits,
            $maxDigits
        );
    }

    /** @dataProvider intParserDataProvider */
    public function testIntParser(
        $cParser,
        $configuration,
        $input,
        $expectedResult,
        $expectedAttribute
    ) {
        $cfg = $this->getParserConfig($cParser, $configuration);
        $parser = $this->pm->build($cParser, $configuration);
        $configuration[1] = $configuration[1] ?? 1;
        $configuration[2] = $configuration[2] ?? 0;

        $expectedValue = $expectedAttribute = $configuration[0] ?? null;

        $this->doTest(
            $cfg,
            $parser,
            $input,
            $expectedResult,
            $expectedValue,
            $expectedAttribute
        );
    }

    public function intParserDataProvider()
    {
        $scenarios = [
            [
                // policies for which this scenario applies
                'parsers' => [
                    IntParser::class,
                    UIntParser::class,
                    BinaryParser::class,
                    HexParser::class,
                    OctParser::class,
                ],
                // strings to parse in this scenario
                'input' => [
                    '',
                    ' ',
                    '+ 123',
                    '- 123'
                ],
                // which values to expect in this scenario
                // null: all values
                'configurations' => [
                    [],
                    [123],
                ],
                // expected parsing result
                'expectedResult' => false,
            ],
            [
                'parsers' => [
                    IntParser::class,
                    UIntParser::class,
                ],
                'input' => [
                    '+123',
                    '123'
                ],
                'configurations' => [
                    [],
                    [123],
                ],
                'expectedResult' => true,
                // expected raw attribute value (only necessary if
                // expectedResult = true
                // tests will get added for value casted to each type
                // of boolean, int, double, array, string, null and
                // 'unused'
                'expectedAttribute' => 123,
            ],
            [
                'parsers' => [
                    IntParser::class,
                    UIntParser::class,
                ],
                'configurations' => [
                    [],
                    [ 12 ],
                ],
                'input' => [
                    '+12 3',
                    '12 3'
                ],
                'expectedResult' => true,
                'expectedAttribute' => 12,
            ],
            [
                'parsers' => [
                    IntParser::class,
                ],
                'configurations' => [
                    [],
                    [ -12 ]
                ],
                'input' => [
                    '-12 3',
                ],
                'expectedResult' => true,
                'expectedAttribute' => -12,
            ],
            [
                'parsers' => [
                    IntParser::class,
                ],
                'input' => [
                    '-123',
                ],
                'configurations' => [
                    [],
                    [-123],
                ],
                'expectedResult' => true,
                'expectedAttribute' => -123,
            ],
            [
                'parsers' => [
                    IntParser::class,
                    UIntParser::class,
                    BinaryParser::class,
                    HexParser::class,
                    OctParser::class,
                ],
                'input' => [
                    '123',
                    '+123',
                    '+12 3',
                    '12 3',
                    '-123',
                    '- 123',
                    '-12 3'
                ],
                'configurations' => [
                    [456],
                ],
                'expectedResult' => false,
            ],
            [
                'parsers' => [
                    UIntParser::class,
                    BinaryParser::class,
                    HexParser::class,
                    OctParser::class,
                ],
                'input' => [
                    '-123',
                    '-12 3',
                ],
                'configurations' => [
                    [],
                    [-123],
                    [456]
                ],
                'expectedResult' => false,
            ],
            [
                'parsers' => [
                    BinaryParser::class,
                    HexParser::class,
                    OctParser::class,
                ],
                'input' => [
                    '+123',
                    '+12 3',
                ],
                'configurations' => [
                    [],
                    [123],
                    [456],
                ],
                'expectedResult' => false,
            ],
            [
                'parsers' => [
                    HexParser::class,
                ],
                'input' => [
                    '1a2b',
                ],
                'configurations' => [
                    [],
                    [hexdec('1a2b')],
                ],
                'expectedResult' => true,
                'expectedAttribute' => hexdec('1a2b')
            ],
            [
                'parsers' => [
                    OctParser::class,
                ],
                'input' => [
                    '123',
                ],
                'configurations' => [
                    [],
                    [octdec('123')],
                ],
                'expectedResult' => true,
                'expectedAttribute' => octdec('123')
            ],
            [
                'parsers' => [
                    BinaryParser::class,
                ],
                'input' => [
                    '110110110',
                ],
                'configurations' => [
                    [],
                    [bindec('110110110')],
                ],
                'expectedResult' => true,
                'expectedAttribute' => bindec('110110110')
            ],
            [
                'parsers' => [
                    UIntParser::class,
                ],
                'input' => [
                    '+123',
                    '+1234',
                    '+12345',
                ],
                'configurations' => [
                    [null, 3, 5],
                ],
                'expectedResult' => true,
                'expectedAttribute' => null
            ],
            [
                'parsers' => [
                    UIntParser::class,
                ],
                'input' => [
                    '+12',
                    '+123456'
                ],
                'configurations' => [
                    [null, 3, 5 ],
                ],
                'expectedResult' => false,
            ],
            [
                'parsers' => [
                    IntParser::class,
                ],
                'input' => [
                    '-123',
                    '-1234',
                    '-12345',
                    '+12345',
                    '+1234',
                    '+123'
                ],
                'configurations' => [
                    [ null, 3, 5 ]
                ],
                'expectedResult' => true,
                'expectedAttribute' => null
            ],
            [
                'parsers' => [
                    IntParser::class,
                ],
                'input' => [
                    '-12',
                    '-123456',
                    '+123456',
                    '+12'
                ],
                'configurations' => [
                    [ null, 3, 5 ]
                ],
                'minDigits' => 3,
                'maxDigits' => 5,
                'expectedResult' => false,
            ],
            [
                'parsers' => [
                    IntParser::class,
                    UIntParser::class,
                    BinaryParser::class,
                    HexParser::class,
                    OctParser::class,
                ],
                'input' => [
                    '11011',
                    '1101',
                    '110',
                ],
                'configurations' => [
                    [null, 3, 5],
                ],
                'expectedResult' => true,
                'expectedAttribute' => null
            ],
            [
                'parsers' => [
//                     ShortParser,
//                     UShortParser,
                    IntParser::class,
                    UIntParser::class,
//                     LongParser::class,
//                     ULongParser::class,
//                     LongLongParser::class,
//                     ULongLongParser::class,
                    BinaryParser::class,
                    HexParser::class,
                    OctParser::class,
                ],
                'input' => [
                    '11',
                    '111111'
                ],
                'configurations' => [
                    [null, 3, 5],
                ],
                'expectedResult' => false,
            ],
        ];

        foreach ($scenarios as $scenario) {
            $inputs = $scenario['input'];
            $parsers = $scenario['parsers'];
            $configurations = $scenario['configurations'];
            $expectedResult = $scenario['expectedResult'];
            foreach ($parsers as $parser) {
                foreach ($inputs as $input) {
                    foreach ($configurations as $configuration) {
                        $tests[] = [
                            $parser,                // integer parser to use
                            $configuration,         // parser configurations
                            $input,                 // string to parse
                            $expectedResult,        // expected result of parse (true/false)
                            null                    // expected attribute
                        ];
                    }
                }
            }
        }
        return $tests;
    }
}
