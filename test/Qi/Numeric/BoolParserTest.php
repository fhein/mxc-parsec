<?php

namespace Mxc\Test\Parsec\Qi\Numeric;

use PHPUnit\Framework\TestCase;
use Mxc\Test\Parsec\TestBed;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Numeric\BoolParser;
use Mxc\Parsec\Qi\Numeric\Detail\BoolPolicy;
use Mxc\Parsec\Qi\Char\CharClassParser;
use Mxc\Parsec\Qi\Numeric\Detail\NoCaseBoolPolicy;
use Mxc\Test\Parsec\Qi\Numeric\Assets\BackwardsBoolPolicy;
use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Qi\UnusedAttribute;

class BoolParserTest extends TestCase
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


    /** @dataProvider boolParserDataProvider */
    public function testBoolParser(
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
            $attributeType = null;
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
        $this->testbed->setParser($this->pm->get(BoolParser::class));
    }

    public function boolParserDataProvider()
    {

        // array of attribute types as returned by $parser->getType
        // with true and false values associated with each type
        $typedResults = [
            'boolean'   => [true => true,     false => false],
            'integer'   => [true => 1,        false => 0 ],
            'double'    => [true => 1.0,      false => 0.0 ],
            'NULL'      => [true => null,     false => null],
            'string'    => [true => "1",      false => "" ],
            'array'     => [true => [ true ], false => [ false ]],
            'null'      => [true => true,     false => false],
            'unused'    => [true => 'unused', false => 'unused'],
        ];

        $inputsAndResults =
        [
            // this policy accepts 'true' as true
            // 'false' as false
            BoolPolicy::class =>
            [
                'false' =>
                [
                    ['expectedValue' => false,  'expectedResult' => true],
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => true],

                ],
                'true'  =>
                [
                    ['expectedValue' => true,   'expectedResult' => true],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => true]

                ],
                't' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                'f' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                '' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]
                ],
                ' ' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]
                ],
                'FALSE' =>
                [
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false],

                ],
                'TRUE'  =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                'T' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                'F' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
            ],
            // this policy accepts 'true', 'TRUE' as true,
            // 'false', 'FALSE' as false
            NoCaseBoolPolicy::class =>
            [
                'false' =>
                [
                    ['expectedValue' => false,  'expectedResult' => true],
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => true],

                ],
                'true'  =>
                [
                    ['expectedValue' => true,   'expectedResult' => true],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => true]

                ],
                't' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                'f' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                '' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]
                ],
                ' ' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]
                ],
                'FALSE' =>
                [
                    ['expectedValue' => false,  'expectedResult' => true],
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => true],

                ],
                'TRUE'  =>
                [
                    ['expectedValue' => true,   'expectedResult' => true],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => true]

                ],
                'FaLsE' =>
                [
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false],

                ],
                'TrUe'  =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                'T' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                'F' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
            ],

            BackwardsBoolPolicy::class =>
            [
                'false' =>
                [
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false],

                ],
                'true'  =>
                [
                    ['expectedValue' => true,   'expectedResult' => true],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => true]

                ],
                't' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                'f' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                '' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]
                ],
                ' ' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]
                ],
                'FALSE' =>
                [
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false],

                ],
                'TRUE'  =>
                [
                    ['expectedValue' => true,   'expectedResult' => true],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => true]

                ],
                'T' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                'F' =>
                [
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false]

                ],
                'EURT' =>
                [
                    ['expectedValue' => false,  'expectedResult' => true],
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => true],

                ],
                'EuRt' =>
                [
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false],

                ],
                'eUrT' =>
                [
                    ['expectedValue' => false,  'expectedResult' => false],
                    ['expectedValue' => true,   'expectedResult' => false],
                    ['expectedValue' => null,   'expectedResult' => false],

                ],
            ],
        ];

        foreach ($typedResults as $type => $value) {
            foreach ($inputsAndResults as $policy => $i) {
                foreach ($i as $input => $test) {
                    foreach ($test as $set) {
                        $expectedValue = $set['expectedValue'];
                        $expectedResult = $set['expectedResult'];
                        $tests[] =
                        [
                            $input,                  // string to parse
                            $expectedValue,          // expected value
                            $type,                   // desired attribute type
                            $expectedResult,         // expected parser result (true/false)
                            $value[$expectedValue],  // expected typed attribute
                            false,                   // do not use skipper
                            $policy,                 // boolean policy
                        ];
                        // test pre-skipping
                        $tests[] =
                        [
                            ' ' . $input,            // string to parse
                            $expectedValue,          // expected value
                            $type,                   // desired attribute type
                            $expectedResult,         // expected parser result (true/false)
                            $value[$expectedValue],  // expected typed attribute
                            true,                    // use skipper
                            $policy,                 // boolean policy
                        ];
                        // all tests should return false if pre-skipping is required
                        // but no skipper is defined
                        $tests[] =
                        [
                            ' ' . $input,            // string to parse
                            $expectedValue,          // expected value
                            $type,                   // desired attribute type
                            false,                   // expected parser result (true/false)
                            $value[$expectedValue],  // expected typed attribute
                            false,                   // use skipper
                            $policy,                 // boolean policy
                        ];
                    }
                }
            }
        }
        return $tests;
    }
}
