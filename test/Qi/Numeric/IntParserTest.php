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
use Mxc\Parsec\Qi\Numeric\Detail\BinaryIntPolicy;

class IntParserTest extends TestCase
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
        $expectedValue,
        $attributeType,
        $expectedResult,
        $expectedAttribute,
        $skip,
        $policy,
        $result
    ) {
        return sprintf(
            "Test Set:\n"
            . "Input: %s\n"
            . "Policy: %s\n"
            . "Expected value: %s\n"
            . "Attribute type: %s\n"
            . "Expected result: %s\n"
            . "Expected Attribute: %s\n\n"
            . "Results:\n"
            . "Parsing result: %s\n"
            . "Attribute: %s\n"
            . "Attribute Type: %s",
            $input,
            $policy,
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
        $policy,
        $input,
        $expectedValue,
        $expectedResult,
        $attributeType,
        $expectedAttribute,
        $skip
    ) {

        $this->testbed->setPolicy(new $policy());
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
            $policy
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
                    $expectedValue,
                    $attributeType,
                    $expectedResult,
                    $expectedAttribute,
                    $skip,
                    $policy,
                    $result
                )
            )
        );

        if ($result['result']) {
            if ($attributeType === null) {
                $attributeType = 'boolean';
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
                            $expectedValue,
                            $attributeType,
                            $expectedResult,
                            $expectedAttribute,
                            $skip,
                            $policy,
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
        $this->testbed->setParser($this->pm->get(IntParser::class));
    }

    protected function getTypedAttributes(int $i)
    {
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
                'policies' => [
                    DecimalIntPolicy::class,
                    DecimalUIntPolicy::class,
                    HexIntPolicy::class,
                    OctIntPolicy::class,
                    BinaryIntPolicy::class,
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
                'policies' => [
                    DecimalIntPolicy::class,
                    DecimalUIntPolicy::class,
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
                'policies' => [
                    DecimalIntPolicy::class,
                    DecimalUIntPolicy::class,
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
                'policies' => [
                    DecimalIntPolicy::class,
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
                'policies' => [
                    DecimalIntPolicy::class,
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
                'policies' => [
                    DecimalIntPolicy::class,
                    DecimalUIntPolicy::class,
                    HexIntPolicy::class,
                    OctIntPolicy::class,
                    BinaryIntPolicy::class,
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
                'policies' => [
                    DecimalUIntPolicy::class,
                    HexIntPolicy::class,
                    OctIntPolicy::class,
                    BinaryIntPolicy::class,
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
                'policies' => [
                    HexIntPolicy::class,
                    OctIntPolicy::class,
                    BinaryIntPolicy::class,
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
                'policies' => [
                    HexIntPolicy::class,
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
                'policies' => [
                    OctIntPolicy::class,
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
                'policies' => [
                    BinaryIntPolicy::class,
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
        ];

        foreach ($scenarios as $scenario) {
            $inputs = $scenario['input'];
            $policies = $scenario['policies'];
            $expectedValues = $scenario['expectedValue'];
            $expectedResult = $scenario['expectedResult'];
            foreach ($policies as $policy) {
                foreach ($inputs as $input) {
                    foreach ($expectedValues as $expectedValue) {
                        if ($expectedResult === false) {
                            $tests[] = [
                                $policy,                // integer policy to use
                                $input,                 // string to parse
                                $expectedValue,         // acceptable value or null for any
                                $expectedResult,        // expected result of parse (true/false)
                                null,                   // requested attribute type
                                null,                   // expected typed attribute
                                false,                  // do not use skipper
                            ];
                            continue;
                        }
                        // parsing should fail if no skipper defined
                        // and skippable content is prepended to input
                        $tests[] = [
                            $policy,                // integer policy to use
                            ' '. $input,            // string to parse
                            $expectedValue,         // acceptable value or null for any
                            false,                  // expected result of parse (true/false)
                            null,                   // requested attribute type
                            null,                   // expected typed attribute
                            false,                  // do not use skipper
                        ];
                        $typedAttributes = $this->getTypedAttributes($scenario['expectedAttribute']);
                        foreach ($typedAttributes as $type => $value) {
                            $tests[] = [
                                $policy,                // integer policy to use
                                $input,                 // string to parse
                                $expectedValue,         // acceptable value or null for any
                                $expectedResult,        // expected result of parse (true/false)
                                $type,                  // requested attribute type
                                $value,                 // expected typed attribute
                                false,                  // do not use skipper
                            ];
                            // succeeding tests should also succeed if skipper is available
                            $tests[] = [
                                $policy,                // integer policy to use
                                $input,                 // string to parse
                                $expectedValue,         // acceptable value or null for any
                                $expectedResult,        // expected result of parse (true/false)
                                $type,                  // requested attribute type
                                $value,                 // expected typed attribute
                                true,                   // do use skipper
                            ];
                            // succeeding tests should also succeed if skipper is available
                            // and skippable content is prepended to input
                            $tests[] = [
                                $policy,                // integer policy to use
                                ' '. $input,            // string to parse
                                $expectedValue,         // acceptable value or null for any
                                $expectedResult,        // expected result of parse (true/false)
                                $type,                  // requested attribute type
                                $value,                 // expected typed attribute
                                true,                   // do use skipper
                            ];
                        }
                    }
                }
            }
        }
        return $tests;
    }
}
