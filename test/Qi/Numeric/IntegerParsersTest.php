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
    protected function getParserConfig(string $parser, int $minDigits, $maxDigits)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    Min Digits: %d\n"
            . "    Max Digits: %d\n",
            $parser,
            $minDigits,
            $maxDigits
        );
    }

    /** @dataProvider intParserDataProvider */
    public function testIntParser(
        $cParser,
        $input,
        $expectedValue,
        $expectedResult,
        $expectedAttributeType,
        $expectedAttribute,
        $minDigits = 1,
        $maxDigits = -1
    ) {
        $cfg = $this->getParserConfig($cParser, $minDigits, $maxDigits);
        $parser = $this->pm->build($cParser, [ $minDigits, $maxDigits]);

        $expectedAttributeType = $expectedAttributeType ?? 'integer';
        if ($expectedValue !== null) {
            $expectedAttribute = $this->getTypedExpectedValue($expectedAttributeType, $expectedValue);
        }

        $this->doTest(
            $cfg,
            $parser,
            $input,
            $expectedResult,
            $expectedValue,
            $expectedAttribute,
            $expectedAttributeType
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
                'expectedValue' => [
                    null,
                    123,
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
                'expectedValue' => [
                    null,
                    123,
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
                'input' => [
                    '+12 3',
                    '12 3'
                ],
                'expectedValue' => [
                    null,
                    12,
                ],
                'expectedResult' => true,
                'expectedAttribute' => 12,
            ],
            [
                'parsers' => [
                    IntParser::class,
                ],
                'input' => [
                    '-12 3',
                ],
                'expectedValue' => [
                    null,
                    -12,
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
                'expectedValue' => [
                    null,
                    -123,
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
                'expectedValue' => [456],
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
                'expectedValue' => [
                    null,
                    -123,
                    456
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
                'expectedValue' => [
                    null,
                    123,
                    456
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
                'expectedValue' => [
                    null,
                    hexdec('1a2b'),
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
                'expectedValue' => [
                    null,
                    octdec('123'),
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
                'expectedValue' => [
                    null,
                    bindec('110110110'),
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
                'expectedValue' => [
                    null,
                ],
                'minDigits' => 3,
                'maxDigits' => 5,
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
                'expectedValue' => [
                    null,
                ],
                'minDigits' => 3,
                'maxDigits' => 5,
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
                'expectedValue' => [
                    null,
                ],
                'minDigits' => 3,
                'maxDigits' => 5,
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
                'expectedValue' => [
                    null,
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
                'expectedValue' => [
                    null,
                ],
                'minDigits' => 3,
                'maxDigits' => 5,
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
                'expectedValue' => [
                    null,
                ],
                'minDigits' => 3,
                'maxDigits' => 5,
                'expectedResult' => false,
            ],
        ];

        foreach ($scenarios as $scenario) {
            $inputs = $scenario['input'];
            $parsers = $scenario['parsers'];
            $expectedValues = $scenario['expectedValue'];
            $expectedResult = $scenario['expectedResult'];
            $minDigits = $scenario['minDigits'] ?? 1;
            $maxDigits = $scenario['maxDigits'] ?? 0;
            foreach ($parsers as $parser) {
                foreach ($inputs as $input) {
                    foreach ($expectedValues as $expectedValue) {
                        $tests[] = [
                            $parser,                // integer parser to use
                            $input,                 // string to parse
                            $expectedValue,         // acceptable value or null for any
                            $expectedResult,        // expected result of parse (true/false)
                            null,                   // requested attribute type
                            null,                   // expected typed attribute
                            $minDigits,             // minimum # of digits
                            $maxDigits,             // maximum # of digits
                        ];
                    }
                }
            }
        }
        return $tests;
    }
}
