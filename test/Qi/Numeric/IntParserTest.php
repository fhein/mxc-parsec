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
        $this->testbed = new TestBed();
        $this->testbed->setParser($this->pm->get(IntParser::class));
    }

    public function intParserDataProvider()
    {

        // array of attribute types as returned by $parser->getType
        // with true and false values associated with each type

        $policies = [
            DecimalIntPolicy::class => [
                '' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 123 ($expectedValue = 123)
                    [
                        'expectedValue' => 123,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                ' ' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 123 ($expectedValue = 123)
                    [
                        'expectedValue' => 123,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '123' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 123 ($expectedValue = 123)
                    [
                        'expectedValue' => 123,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '+123' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 123 ($expectedValue = 123)
                    [
                        'expectedValue' => 123,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '12 3' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 12,
                            'double' => (double)12,
                            'NULL' => null,
                            'string' => '12',
                            'array' => [12],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 12 ($expectedValue = 12)
                    [
                        'expectedValue' => 12,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 12,
                            'double' => (double)12,
                            'NULL' => null,
                            'string' => '12',
                            'array' => [12],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 12,
                            'double' => (double)12,
                            'NULL' => null,
                            'string' => '12',
                            'array' => [12],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '-123' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => -123,
                            'double' => (double)(-123),
                            'NULL' => null,
                            'string' => '-123',
                            'array' => [-123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept -123 ($expectedValue = -123)
                    [
                        'expectedValue' => -123,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => -123,
                            'double' => (double)(-123),
                            'NULL' => null,
                            'string' => '-123',
                            'array' => [-123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => -123,
                            'double' => (double)(-123),
                            'NULL' => null,
                            'string' => '-123',
                            'array' => [-123],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '-12 3' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => -12,
                            'double' => (double)(-12),
                            'NULL' => null,
                            'string' => '-12',
                            'array' => [-12],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept -12 ($expectedValue = -12)
                    [
                        'expectedValue' => -12,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => -12,
                            'double' => (double)(-12),
                            'NULL' => null,
                            'string' => '-12',
                            'array' => [-12],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 456,
                            'double' => (double)456,
                            'NULL' => null,
                            'string' => '456',
                            'array' => [456],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '- 123' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => -123,
                            'double' => (double)(-123),
                            'NULL' => null,
                            'string' => '-123',
                            'array' => [-123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept -123 ($expectedValue = -123)
                    [
                        'expectedValue' => -123,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => -123,
                            'double' => (double)(-123),
                            'NULL' => null,
                            'string' => '-123',
                            'array' => [-123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 456,
                            'double' => (double)456,
                            'NULL' => null,
                            'string' => '456',
                            'array' => [456],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '+ 123' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)(123),
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 123 ($expectedValue = 123)
                    [
                        'expectedValue' => 123,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)(123),
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 456,
                            'double' => (double)456,
                            'NULL' => null,
                            'string' => '456',
                            'array' => [456],
                            'unused' => 'unused',
                        ],
                    ],
                ],
            ],
            DecimalUIntPolicy::class => [
                '' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 123 ($expectedValue = 123)
                    [
                        'expectedValue' => 123,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                ' ' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 123 ($expectedValue = 123)
                    [
                        'expectedValue' => 123,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '123' => [
                    // accept any unsigned integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 123 ($expectedValue = 123)
                    [
                        'expectedValue' => 123,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '-123' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => -123,
                            'double' => (double)(-123),
                            'NULL' => null,
                            'string' => '-123',
                            'array' => [-123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept -123 ($expectedValue = -123)
                    [
                        'expectedValue' => -123,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => -123,
                            'double' => (double)(-123),
                            'NULL' => null,
                            'string' => '-123',
                            'array' => [-123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => -123,
                            'double' => (double)(-123),
                            'NULL' => null,
                            'string' => '-123',
                            'array' => [-123],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '-12 3' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => -12,
                            'double' => (double)(-12),
                            'NULL' => null,
                            'string' => '-12',
                            'array' => [-12],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept -12 ($expectedValue = -12)
                    [
                        'expectedValue' => -12,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => -12,
                            'double' => (double)(-12),
                            'NULL' => null,
                            'string' => '-12',
                            'array' => [-12],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 456,
                            'double' => (double)456,
                            'NULL' => null,
                            'string' => '456',
                            'array' => [456],
                            'unused' => 'unused',
                        ],
                    ],
                ],
                '+123' => [
                    // accept any unsigned integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)123,
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 123 ($expectedValue = 123)
                    [
                        'expectedValue' => 123,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)(123),
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 456,
                            'double' => (double)456,
                            'NULL' => null,
                            'string' => '456',
                            'array' => [456],
                            'unused' => 'unused',
                        ],
                    ],

                ],
                // end parsing at delimiter
                '+12 3' => [
                    // accept any unsigned integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 12,
                            'double' => (double)12,
                            'NULL' => null,
                            'string' => '12',
                            'array' => [12],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 12 ($expectedValue = 12)
                    [
                        'expectedValue' => 12,
                        'expectedResult' => true,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 12,
                            'double' => (double)(12),
                            'NULL' => null,
                            'string' => '12',
                            'array' => [12],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 456,
                            'double' => (double)456,
                            'NULL' => null,
                            'string' => '456',
                            'array' => [456],
                            'unused' => 'unused',
                        ],
                    ],

                ],
                // do not accept input with delimiters between
                // sign and digits
                '+ 123' => [
                    // accept any integer ($expectedValue = 0)
                    [
                        'expectedValue' => null,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)(123),
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 123 ($expectedValue = 123)
                    [
                        'expectedValue' => 123,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => true,
                            'integer' => 123,
                            'double' => (double)(123),
                            'NULL' => null,
                            'string' => '123',
                            'array' => [123],
                            'unused' => 'unused',
                        ],
                    ],
                    // accept 456 ($expectedValue = 456)
                    [
                        'expectedValue' => 456,
                        'expectedResult' => false,
                        'expectedAttributes' => [
                            'boolean' => null,
                            'integer' => 456,
                            'double' => (double)456,
                            'NULL' => null,
                            'string' => '456',
                            'array' => [456],
                            'unused' => 'unused',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($policies as $policy => $setup) {
            foreach ($setup as $input => $scenario) {
                foreach ($scenario as $test) {
                    $expectedValue = $test['expectedValue'];
                    $expectedResult = $test['expectedResult'];
                    foreach ($test['expectedAttributes'] as $type => $expectedAttribute) {
                        $tests[] = [
                            $input,                 // string to parse
                            $expectedValue,         // acceptable value or null for any
                            $type,                  // requested attribute type
                            $expectedResult,        // expected result of parse (true/false)
                            $expectedAttribute,     // expected typed attribute
                            false,                  // do not use skipper
                            $policy                 // integer policy to use
                        ];
                        // test pre-skipping
                        $tests[] = [
                            ' ' . $input,           // string to parse
                            $expectedValue,         // acceptable value or null for any
                            $type,                  // requested attribute type
                            $expectedResult,        // expected result of parse (true/false)
                            $expectedAttribute,     // expected typed attribute
                            true,                   // use skipper
                            $policy                 // integer policy to use
                        ];
                        // all tests should return false if pre-skipping is required
                        // without a skipper being defined
                        $tests[] =
                        [
                            ' ' . $input,            // string to parse
                            $expectedValue,          // expected value
                            $type,                   // requested attribute type
                            false,                   // expected parser result (true/false)
                            $value[$expectedValue],  // expected typed attribute
                            false,                   // do not use skipper
                            $policy,                 // boolean policy
                        ];
                    }
                }
            }
        }

        return $tests;
    }
}
