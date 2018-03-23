<?php

namespace Mxc\Test\Parsec\Qi\Numeric;

use PHPUnit\Framework\TestCase;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Numeric\BoolParser;
use Mxc\Parsec\Qi\Numeric\Detail\BoolPolicy;
use Mxc\Parsec\Qi\Char\CharClassParser;
use Mxc\Parsec\Qi\Numeric\Detail\NoCaseBoolPolicy;
use Mxc\Test\Parsec\Qi\Numeric\Assets\BackwardsBoolPolicy;
use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Qi\UnusedAttribute;

class BoolParserTest extends ParserTestBed
{

    protected $testbed;
    protected $domain;
    protected $skipper;

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

    /** @dataProvider boolParserDataProvider */
    public function testBoolParser(
        $policy,
        $input,
        $expectedValue,
        $expectedResult,
        $attributeType,
        $expectedAttribute,
        $skip
    ) {
        $parser = $this->pm->build(BoolParser::class, [ new $policy() ]);

        if ($attributeType === 'null') {
            $attributeType = null;
        }
        if ($expectedAttribute === 'unused') {
            $expectedAttribute = $this->pm->get(UnusedAttribute::class);
        }
        $skipper = $skip ? $this->getSkipper() : null;
        $result = $this->xTest(
            $parser,
            $input,
            $expectedValue,
            $attributeType,
            $skipper,
            $expectedAttribute,
            $expectedResult
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

    protected function getTypedAttributes(bool $i)
    {
        return [
            'boolean' => $i,
            'integer' => (int)$i,
            'double'  => (double) $i,
            'array'   => [ $i ],
            'string'  => strval($i),
            'NULL'    => null,
            'unused'  => 'unused',
        ];
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
            foreach ($policies as $policy) {
                foreach ($inputs as $input) {
                    foreach ($expectedValues as $expectedValue) {
                        if ($expectedResult === false) {
                            // verify that parser fails
                            $tests[] = [
                                $policy,                // boolean policy to use
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
                        // if no attribute type is requested the returned attribute
                        // should be of the default type of the according parser
                        $tests[] = [
                            $policy,                // integer policy to use
                            $input,                 // string to parse
                            $expectedValue,         // acceptable value or null for any
                            $expectedResult,        // expected result of parse (true/false)
                            null,                   // requested attribute type (null: default)
                            $expectedAttribute,     // expected typed attribute
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
