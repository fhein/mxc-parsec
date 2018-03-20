<?php

namespace Mxc\Test\Parsec\Qi\Numeric;

use PHPUnit\Framework\TestCase;
use Mxc\Test\Parsec\TestBed;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Numeric\IntParser;
use Mxc\Parsec\Qi\Char\CharClassParser;
use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Qi\UnusedAttribute;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalIntPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalUIntPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\HexIntPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\OctIntPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\BinIntPolicy;
use Mxc\Parsec\Qi\Numeric\UIntParser;
use Mxc\Parsec\Qi\Numeric\BinaryParser;
use Mxc\Parsec\Qi\Numeric\HexParser;
use Mxc\Parsec\Qi\Numeric\OctParser;

class IntegerParsersTest extends TestCase
{
    protected $testbed;
    protected $domain;
    protected $skipper;
    protected $pm;

    protected function getSkipper()
    {
        if (! $this->skipper) {
            $this->skipper = $this->pm->build(CharClassParser::class, [ 'space' ]);
        }
        return $this->skipper;
    }

    protected function getParserResult(
        $input,
        $minDigits,
        $maxDigits,
        $expectedValue,
        $attributeType,
        $expectedResult,
        $expectedAttribute,
        $skip,
        $parser,
        $result
    ) {
        return sprintf(
            "Test Set:\n"
            . "  Parser: %s\n"
            . "  Input: %s\n"
            . "  Min digits: %d\n"
            . "  Max digits: %d\n"
            . "  Expected value: %s\n"
            . "  Attribute type: %s\n"
            . "  Expected result: %s\n"
            . "  Expected Attribute: %s\n\n"
            . "  Results:\n"
            . "  Parsing result: %s\n"
            . "  Attribute: %s\n"
            . "  Attribute Type: %s",
            $parser,
            $input,
            $minDigits,
            $maxDigits,
            var_export($expectedValue, true),
            $attributeType,
            var_export($expectedResult, true),
            gettype($expectedAttribute) === 'object' ? get_class($expectedAttribute) : $expectedAttribute,
            var_export($result['result'], true),
            var_export($result['attribute'], true),
            $result['attribute_type']
        );
    }

    /** @dataProvider intParserDataProvider */
    public function testIntParser(
        $parser,
        $input,
        $expectedValue,
        $expectedResult,
        $attributeType,
        $expectedAttribute,
        $skip,
        $minDigits = 1,
        $maxDigits = -1
    ) {
        $this->testbed->setParser($this->pm->build($parser, [ $minDigits, $maxDigits]));
        if ($attributeType === 'null') {
            $attributeType = 'NULL';
        }
        if ($expectedAttribute === 'unused') {
            $expectedAttribute = $this->pm->get(UnusedAttribute::class);
        }
        $skipper = $skip ? $this->getSkipper() : null;
        $result = $this->testbed->test(
            $input,
            $expectedValue,
            $attributeType,
            $skipper,
            $expectedAttribute,
            $expectedResult,
            $parser
        );
        $this->assertSame(
            $expectedResult,
            $result['result'],
            sprintf(
                "\nResult (". var_export($result['result'], true). ')'
                . ' is different from expected result '
                . '(' . var_export($expectedResult, true). ")\n\n%s\n",
                $this->getParserResult(
                    $input,
                    $minDigits,
                    $maxDigits,
                    $expectedValue,
                    $attributeType,
                    $expectedResult,
                    $expectedAttribute,
                    $skip,
                    $parser,
                    $result
                )
            )
        );

        if ($result['result']) {
            if ($attributeType === null) {
                $attributeType = 'integer';
            }
            $this->assertSame(
                $attributeType,
                $result['attribute_type'],
                sprintf(
                    "Target attribute type (%s) and received attribute type (%s) are different.",
                    $attributeType,
                    $result['attribute_type']
                )
            );

            if ($expectedAttribute !== null) {
                $this->assertSame(
                    $expectedAttribute,
                    $result['attribute'],
                    sprintf(
                        "Expected attribute does not match received attribute.\n\n%s",
                        $this->getParserResult(
                            $input,
                            $minDigits,
                            $maxDigits,
                            $expectedValue,
                            $attributeType,
                            $expectedResult,
                            $expectedAttribute,
                            $skip,
                            $parser,
                            $result
                        )
                    )
                );
            }
        }
    }

    public function setUp()
    {
        $this->pm = new ParserManager();
        $this->testbed = new TestBed();
    }

    protected function getTypedAttributes(int $i = null)
    {
        // if test should ignore the returned attribute value
        // consider only the 'unused' case
        if ($i === null) {
            return ['unused' => 'unused'];
        }
        return [
            'boolean' => (bool) $i,
            'integer' => $i,
            'double'  => (double) $i,
            'array'   => [ $i ],
            'string'  => strval($i),
            'NULL'    => null,
            'unused'  => 'unused',
        ];
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
                    IntParser::class,
                    UIntParser::class,
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
            $maxDigits = $scenario['maxDigits'] ?? -1;
            foreach ($parsers as $parser) {
                foreach ($inputs as $input) {
                    foreach ($expectedValues as $expectedValue) {
                        if ($expectedResult === false) {
                            $tests[] = [
                                $parser,                // integer parser to use
                                $input,                 // string to parse
                                $expectedValue,         // acceptable value or null for any
                                $expectedResult,        // expected result of parse (true/false)
                                null,                   // requested attribute type
                                null,                   // expected typed attribute
                                false,                  // do not use skipper
                                $minDigits,             // minimum # of digits
                                $maxDigits,             // maximum # of digits
                            ];
                            continue;
                        }
                        // parsing should fail if no skipper defined
                        // and skippable content is prepended to input
                        $tests[] = [
                            $parser,                // integer parser to use
                            ' '. $input,            // string to parse
                            $expectedValue,         // acceptable value or null for any
                            false,                  // expected result of parse (true/false)
                            null,                   // requested attribute type
                            null,                   // expected typed attribute
                            false,                  // do not use skipper
                            $minDigits,             // minimum # of digits
                            $maxDigits,             // maximum # of digits
                        ];

                        // if no attribute type is requested the returned attribute
                        // should be of the default type of the according parser
                        $tests[] = [
                            $parser,                // integer parser to use
                            $input,                 // string to parse
                            $expectedValue,         // acceptable value or null for any
                            $expectedResult,        // expected result of parse (true/false)
                            null,                   // requested attribute type (null: default)
                            null,                   // expected typed attribute
                            false,                  // do not use skipper
                            $minDigits,             // minimum # of digits
                            $maxDigits,             // maximum # of digits
                        ];

                        $typedAttributes = $this->getTypedAttributes($scenario['expectedAttribute']);
                        foreach ($typedAttributes as $type => $value) {
                            $tests[] = [
                                $parser,                // integer parser to use
                                $input,                 // string to parse
                                $expectedValue,         // acceptable value or null for any
                                $expectedResult,        // expected result of parse (true/false)
                                $type,                  // requested attribute type
                                $value,                 // expected typed attribute
                                false,                  // do not use skipper
                                $minDigits,             // minimum # of digits
                                $maxDigits,             // maximum # of digits
                            ];
                            // succeeding tests should also succeed if skipper is available
                            $tests[] = [
                                $parser,                // integer parser to use
                                $input,                 // string to parse
                                $expectedValue,         // acceptable value or null for any
                                $expectedResult,        // expected result of parse (true/false)
                                $type,                  // requested attribute type
                                $value,                 // expected typed attribute
                                true,                   // do use skipper
                                $minDigits,             // minimum # of digits
                                $maxDigits,             // maximum # of digits
                            ];
                            // succeeding tests should also succeed if skipper is available
                            // and skippable content is prepended to input
                            $tests[] = [
                                $parser,                // integer parser to use
                                ' '. $input,            // string to parse
                                $expectedValue,         // acceptable value or null for any
                                $expectedResult,        // expected result of parse (true/false)
                                $type,                  // requested attribute type
                                $value,                 // expected typed attribute
                                true,                   // do use skipper
                                $minDigits,             // minimum # of digits
                                $maxDigits,             // maximum # of digits
                            ];
                        }
                    }
                }
            }
        }
        return $tests;
    }
}
