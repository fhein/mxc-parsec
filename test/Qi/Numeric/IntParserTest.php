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
//                new CharClassParser($this->pm->get(Domain::class), 'space');
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
        $input,
        $expectedValue,
        $attributeType,
        $expectedResult,
        $expectedAttribute,
        $skip,
        $policy
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
        $p = $this->pm->get(IntParser::class);
        $this->testbed = new TestBed($this->pm->get(IntParser::class));
    }

    public function intParserDataProvider()
    {

        // array of attribute types as returned by $parser->getType
        // with true and false values associated with each type

        $policies = [
            DecimalIntPolicy::class => [
                'input' => '123',
                'expectedValues' => [
                    [
                        'value' => null,
                        'types' => [
                            'boolean' => [
                                'expectedResult' => true,
                                'expectedAttribute' => true,
                            ],
                            'integer' => [
                                'expectedResult' => true,
                                'expectedAttribute' => 123,
                            ],
                            'double' => [
                                'expectedResult' => true,
                                'expectedAttribute' => (double)123,
                            ],
                            'NULL' => [
                                'expectedResult' => true,
                                'expectedAttribute' => null,
                            ],
                            'string' => [
                                'expectedResult' => true,
                                'expectedAttribute' => '123',
                            ],
                            'array' => [
                                'expectedResult' => true,
                                'expectedAttribute' => [123],
                            ],
                            'unused' => [
                                'expectedResult' => true,
                                'expectedAttribute' => 'unused',
                            ],
                        ],
                    ],
                    [
                        'value' => 123,
                        'types' => [
                            'boolean' => [
                                'expectedResult' => true,
                                'expectedAttribute' => true,
                            ],
                            'integer' => [
                                'expectedResult' => true,
                                'expectedAttribute' => 123,
                            ],
                            'double' => [
                                'expectedResult' => true,
                                'expectedAttribute' => (double)123,
                            ],
                            'NULL' => [
                                'expectedResult' => true,
                                'expectedAttribute' => null,
                            ],
                            'string' => [
                                'expectedResult' => true,
                                'expectedAttribute' => '123',
                            ],
                            'array' => [
                                'expectedResult' => true,
                                'expectedAttribute' => [123],
                            ],
                            'unused' => [
                                'expectedResult' => true,
                                'expectedAttribute' => 'unused',
                            ],
                        ],
                    ],
                    [
                        'value' => 456,
                        'types' => [
                            'boolean' => [
                                'expectedResult' => false,
                                'expectedAttribute' => null,
                            ],
                            'integer' => [
                                'expectedResult' => false,
                                'expectedAttribute' => 123,
                            ],
                            'double' => [
                                'expectedResult' => false,
                                'expectedAttribute' => (double)123,
                            ],
                            'NULL' => [
                                'expectedResult' => false,
                                'expectedAttribute' => null,
                            ],
                            'string' => [
                                'expectedResult' => false,
                                'expectedAttribute' => '123',
                            ],
                            'array' => [
                                'expectedResult' => false,
                                'expectedAttribute' => [123],
                            ],
                            'unused' => [
                                'expectedResult' => false,
                                'expectedAttribute' => 'unused',
                            ],
                        ],
                    ],
                ]
            ]
        ];

        foreach ($policies as $policy => $setup) {
            $input = $setup['input'];
            foreach ($setup['expectedValues'] as $test) {
                $expectedValue = $test['value'];
                foreach ($test['types'] as $type => $results) {
                    $expectedResult = $results['expectedResult'];
                    $expectedAttribute = $results['expectedAttribute'];

                    $tests[] = [
                        $input,
                        $expectedValue,
                        $type,
                        $expectedResult,
                        $expectedAttribute,
                        false,
                        $policy
                    ];
                }
            }
        }

        return $tests;
        $tests = [
            // parser should return true
            ['123', null,   'boolean',  true,   true,           false, DecimalIntPolicy::class],
            ['123', null,   'integer',  true,   123,            false, DecimalIntPolicy::class],
            ['123', null,   'double',   true,   (double)123,    false, DecimalIntPolicy::class],
            ['123', null,   'NULL',     true,   null,           false, DecimalIntPolicy::class],
            ['123', null,   'string',   true,   '123',          false, DecimalIntPolicy::class],
            ['123', null,   'array',    true,   [123],          false, DecimalIntPolicy::class],
            ['123', null,   'unused',   true,   'unused',       false, DecimalIntPolicy::class],
            // parser should return true (success) and attribute false ( because (bool)0 === false)
            ['0',   null,   'boolean',  true,   false,          false, DecimalIntPolicy::class],
            // parser should return false because 456 !== 123
            ['123', 456,    'integer',  false,  null,           false, DecimalIntPolicy::class],
            ['123', 456,    'boolean',  false,  null,           false, DecimalIntPolicy::class],
            ['123', 456,    'double',   false,  null,           false, DecimalIntPolicy::class],
            ['123', 456,    'NULL',     false,  null,           false, DecimalIntPolicy::class],
            ['123', 456,    'string',   false,  null,           false, DecimalIntPolicy::class],
            ['123', 456,    'array',    false,  null,           false, DecimalIntPolicy::class],
            ['123', 456,    'unused',   false,  null,           false, DecimalIntPolicy::class],
        ];

        return $tests;

        // @todo: codepage support

        foreach ($typedResults as $type => $value) {
            foreach ($inputsAndResults as $policy => $i) {
                foreach ($i as $input => $tests) {
                    foreach ($tests as $set) {
                        $test[] =
                        [
                            $input,                     // string to doParse
                            $set['value'],              // expected value
                            $type,                      // desired attribute type
                            $set['result'],             // expected parser result (true/false)
                            $value[$set['value']],      // expected typed attribute
                            false,                      // use skipper?
                            $policy,                    // boolean policy
                        ];
                        $test[] =
                        [
                            ' ' . $input,
                            $set['value'],
                            $type,
                            $set['result'],
                            $value[$set['value']],
                            true,
                            $policy,
                        ];
                        $test[] =
                        [
                            $input . ' ',
                            $set['value'],
                            $type,
                            $set['result'],
                            $value[$set['value']],
                            false,
                            $policy,
                        ];
                    }
                }
            }
        }
        return $test;
    }
}
