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
        $this->testbed = new TestBed($this->pm->get(BoolParser::class));
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
                    ['value' => false,  'result' => true],
                    ['value' => true,   'result' => false],
                    ['value' => null,   'result' => true],

                ],
                'true'  =>
                [
                    ['value' => true,   'result' => true],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => true]

                ],
                't' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                'f' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                '' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]
                ],
                ' ' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]
                ],
                'FALSE' =>
                [
                    ['value' => false,  'result' => false],
                    ['value' => true,   'result' => false],
                    ['value' => null,   'result' => false],

                ],
                'TRUE'  =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                'T' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                'F' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
            ],
            // this policy accepts 'true', 'TRUE' as true,
            // 'false', 'FALSE' as false
            NoCaseBoolPolicy::class =>
            [
                'false' =>
                [
                    ['value' => false,  'result' => true],
                    ['value' => true,   'result' => false],
                    ['value' => null,   'result' => true],

                ],
                'true'  =>
                [
                    ['value' => true,   'result' => true],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => true]

                ],
                't' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                'f' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                '' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]
                ],
                ' ' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]
                ],
                'FALSE' =>
                [
                    ['value' => false,  'result' => true],
                    ['value' => true,   'result' => false],
                    ['value' => null,   'result' => true],

                ],
                'TRUE'  =>
                [
                    ['value' => true,   'result' => true],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => true]

                ],
                'FaLsE' =>
                [
                    ['value' => false,  'result' => false],
                    ['value' => true,   'result' => false],
                    ['value' => null,   'result' => false],

                ],
                'TrUe'  =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                'T' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                'F' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
            ],

            BackwardsBoolPolicy::class =>
            [
                'false' =>
                [
                    ['value' => false,  'result' => false],
                    ['value' => true,   'result' => false],
                    ['value' => null,   'result' => false],

                ],
                'true'  =>
                [
                    ['value' => true,   'result' => true],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => true]

                ],
                't' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                'f' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                '' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]
                ],
                ' ' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]
                ],
                'FALSE' =>
                [
                    ['value' => false,  'result' => false],
                    ['value' => true,   'result' => false],
                    ['value' => null,   'result' => false],

                ],
                'TRUE'  =>
                [
                    ['value' => true,   'result' => true],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => true]

                ],
                'T' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                'F' =>
                [
                    ['value' => true,   'result' => false],
                    ['value' => false,  'result' => false],
                    ['value' => null,   'result' => false]

                ],
                'EURT' =>
                [
                    ['value' => false,  'result' => true],
                    ['value' => true,   'result' => false],
                    ['value' => null,   'result' => true],

                ],
                'EuRt' =>
                [
                    ['value' => false,  'result' => false],
                    ['value' => true,   'result' => false],
                    ['value' => null,   'result' => false],

                ],
                'eUrT' =>
                [
                    ['value' => false,  'result' => false],
                    ['value' => true,   'result' => false],
                    ['value' => null,   'result' => false],

                ],
            ],
        ];


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
