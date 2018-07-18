<?php

namespace Mxc\Test\Parsec;

use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\Char\CharClassParser;
use Mxc\Parsec\Qi\PreSkipper;
use PHPUnit\Framework\TestCase;
use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Exception\UnknownCastException;
use Mxc\Parsec\Attribute\Unused;
use Mxc\Parsec\Qi\Char\Char;
use Mxc\Parsec\Qi\Directive\NoCaseDirective;

/**
 * Base class of all parser tests.
 */
class ParserTestBed extends TestCase
{
    /**
     * @var Parser  Parser used for skipping
     */
    protected $skipper;

    /**
     * @var ParserManager  Parser Manager, a service manager object,
     *                     which can provide all known parsers
     */
    protected $pm;

    /**
     * Default setup for all parser tests
     *
     * {@inheritDoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    public function setUp()
    {
        $this->pm = new ParserManager();
    }

    /**
     * Retrieve the type of a given value
     *
     * @param mixed $value      Value to examine
     * @return string           Type of value
     */
    protected function getType($value)
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }

    /**
     * Retrieve a skipper for preskipping parsers
     *
     * For all standard parser tests we use the 'space'
     * character class parser which accepts all characters
     * for which \IntlChar::isUWhiteSpace($codepoint) returns
     * true.
     *
     * @see SkipperTest.php for tests of arbitrary parsers used as skippers
     *
     * @return Parser   CharClassParser('space')
     */
    protected function getSkipper()
    {
        if (! $this->skipper) {
            $this->skipper = $this->pm->build(CharClassParser::class, [ 'test', 'space' ]);
        }
        return $this->skipper;
    }

    /**
     * Cast a value to a specified type
     *
     * @param string $type  Legal types are 'boolean', 'integer', 'double', 'float'
     *                      'array', 'string', 'NULL', 'unused' and any $value
     *                      constructible class
     * @param unknown $value
     * @return unknown|boolean|number|unknown[]|string|NULL|mixed|object|unknown
     */
    protected function getTypedExpectedValue(string $type = null, $value)
    {
        switch ($type) {
            case null:
                return $value;
            case 'boolean':
                return(bool)$value;
            case 'integer':
                return (int)$value;
            case 'double':
                return (double)$value;
            case 'float':
                return (float)$value;
            case 'array':
                return [ $value ];
            case 'string':
                return strval($value);
            case 'NULL':
                return null;
            case 'unused':
                return new Unused();
            default:
                if (class_exists($type)) {
                    return new $type($value);
                }
        }
        throw new UnknownCastException("Unable to cast to %s. Type is unknown.", $type);
    }

    /**
     * Call parse() of a particular parser with the provided test parameters
     *
     * First checks, if parsing result (true/false) mathches the expected result
     * If so and if expected result was true, checks if retrieved attribute type
     * matches expected attribute type and further checks, if the expected value
     * is returned.
     *
     * Note: No checks on returned attributes are performed, if the expected value
     * was not set. In that case, all returned attributes are ok.
     *
     * @param string $cfg                   Text describing the parser and its configuration
     * @param Parser $parser                Parser to be tested
     * @param Traversable $input            Parser input
     * @param bool $expectedResult          Expected result of parse()
     * @param mixed $expectedValue          Expected value to get parsed
     * @param string $expectedAttributeType Expected type of attribute
     * @param mixed $expectedAttribute      Particular value of attribute
     * @param Parser $skipper               Parser used for skipping
     */
    protected function parserTest(
        string $cfg,
        Parser $parser,
        $input,
        bool $expectedResult,
        int $expectedIteratorPos = null,
        $expectedValue = null,
        $expectedAttribute = null,
        string $expectedAttributeType = null,
        Parser $skipper = null
    ) {
        $iterator = $parser->setSource($input);
        $result = $parser->parse($skipper);
        self::assertSame(
            $expectedResult,
            $result,
            sprintf(
                "%s\n%s\n\n  Unexpected result of parse(): %s. Expected: %s\n",
                $cfg,
                $this->getTestDescription(
                    $parser,
                    $iterator,
                    $input,
                    $expectedValue,
                    $expectedAttributeType,
                    $expectedResult,
                    $expectedAttribute,
                    $result,
                    $skipper,
                    $iterator->key(),
                    $expectedIteratorPos
                ),
                var_export($result, true),
                var_export($expectedResult, true)
            )
        );
        $attribute = $parser->getAttribute();
        if ($result === true) {
            if ($expectedAttributeType !== null) {
                $attributeType = $this->getType($attribute);
                self::assertSame(
                    $expectedAttributeType,
                    $attributeType,
                    sprintf(
                        "%s\n%s\n\n  Unexpected attribute type: %s. Expected: %s\n",
                        $cfg,
                        $this->getTestDescription(
                            $parser,
                            $iterator,
                            $input,
                            $expectedValue,
                            $expectedAttributeType,
                            $expectedResult,
                            $expectedAttribute,
                            $result,
                            $skipper,
                            $iterator->key(),
                            $attribute,
                            $attributeType,
                            $expectedIteratorPos
                        ),
                        $attributeType,
                        $expectedAttributeType
                    )
                );
            }
            if (is_double($expectedAttribute)) {
                if (is_nan($expectedAttribute)) {
                    $this->assertTrue(is_nan($attribute));
                } elseif (is_infinite($expectedAttribute)) {
                    self::assertTrue(is_infinite($attribute));
                }
            } else {
                if ($expectedAttribute !== null) {
                    self::assertEquals(
                        $expectedAttribute,
                        $attribute,
                        sprintf(
                            "%s\n%s\n\n  Unexpected attribute: %s. Expected: %s\n",
                            $cfg,
                            $this->getTestDescription(
                                $parser,
                                $iterator,
                                $input,
                                $expectedValue,
                                $expectedAttributeType,
                                $expectedResult,
                                $expectedAttribute,
                                $result,
                                $skipper,
                                $iterator->key(),
                                $attribute,
                                $attributeType,
                                $expectedIteratorPos
                            ),
                            $attribute ?? 'n/a',
                            $expectedAttribute
                        )
                    );
                }
            }
            if ($expectedValue !== null) {
                if (is_double($expectedValue)) {
                    if (is_nan($expectedValue)) {
                        $this->assertTrue(is_nan($attribute));
                    } elseif (is_infinite($expectedValue)) {
                        self::assertTrue(is_infinite($attribute));
                    }
                } else {
                    $expValue = $this->getTypedExpectedValue($expectedAttributeType, $expectedValue);
                    $attr = $attribute;
                    if ($parser instanceof NoCaseDirective
                        && $this->getType($expValue) === 'string'
                        && $this->getType($attribute) === 'string'
                    ) {
                        // @todo: IntlChar based version of strtolower
                        $attr = strtolower($attr);
                        $expValue = strtolower($expValue);
                    }
                    if (is_float($expectedValue) && is_nan($expectedValue)) {
                        self::assertSame(true, is_nan($attribute));
                    } elseif (is_float($expectedValue) && is_infinite($expectedValue)) {
                        self::assertSame(true, is_infinite($attribute));
                    } else {
                        self::assertEquals(
                            $expValue,
                            $attr,
                            sprintf(
                                "%s\n%s\n\n  Attribute %s does not match expected value %s.\n",
                                $cfg,
                                $this->getTestDescription(
                                    $parser,
                                    $iterator,
                                    $input,
                                    $expectedValue,
                                    $expectedAttributeType,
                                    $expectedResult,
                                    $expectedAttribute,
                                    $result,
                                    $skipper,
                                    $iterator->key(),
                                    $attribute,
                                    $attributeType,
                                    $expectedIteratorPos
                                ),
                                var_export($attribute, true),
                                var_export($expectedAttribute, true)
                            )
                        );
                    }
                }
            }
            if ($expectedIteratorPos !== null) {
                $pos = $iterator->key();
                self::assertSame(
                    $expectedIteratorPos,
                    $pos,
                    sprintf(
                        "%s\n%s\n\n  Iterator position mismatch: %d. Expected: %d\n",
                        $cfg,
                        $this->getTestDescription(
                            $parser,
                            $iterator,
                            $input,
                            $expectedValue,
                            $expectedAttributeType,
                            $expectedResult,
                            $expectedAttribute,
                            $result,
                            $skipper,
                            $pos,
                            $attribute,
                            $attributeType,
                            $expectedIteratorPos
                        ),
                        $pos,
                        $expectedIteratorPos
                    )
                );
            }
        }
    }

    /**
     * Perform tests of a parser based on a given test configuration.
     *
     * First test parser with configuration as retrieved
     * If the parser is a PreSkipper:
     *      Do the same test with skipper supplied
     *      Do the same test with skippable content prepended to input and skipper supplied
     *      Verify that the parser fails, if skippable content gets prepended to input and
     *      no skipper is supplied
     *
     * @param string $cfg                       Text describing the parser and its configuration
     * @param Parser $parser                    Parser to be tested
     * @param Traversable $input                Parser input
     * @param bool $expectedResult              Expected result of parse()
     * @param mixed $expectedValue              Expected value to get parsed
     * @param mixed $expectedAttribute          Particular value of attribute
     * @param string $expectedAttributeType     Expected type of attribute
     */
    protected function doTest(
        string $cfg,
        Parser $parser,
        $input,
        bool $expectedResult,
        $expectedValue = null,
        $expectedAttribute = null,
        string $expectedAttributeType = null,
        $expectedIteratorPos = null
    ) {
        if ($expectedAttributeType === 'null') {
            $attributeType = 'NULL';
        }
        $skipper = $this->getSkipper();

        // perform test with parameters as received
        $this->parserTest(
            $cfg,
            $parser,
            $input,
            $expectedResult,
            $expectedIteratorPos,
            $expectedValue,
            $expectedAttribute,
            $expectedAttributeType,
            $skipper
        );

        // if the parser does not require pre-skipping
        // we are done here
        if (! $parser instanceof PreSkipper) {
            return;
        }

        // succeeding tests should also succeed on same input
        // if skipper is supplied
        $this->parserTest(
            $cfg,
            $parser,
            $input,
            $expectedResult,
            $expectedIteratorPos,
            $expectedValue,
            $expectedAttribute,
            $expectedAttributeType,
            $skipper
        );

        // succeeding tests should also succeed if skipper is supplied
        // and skippable content is prepended to input
        $this->parserTest(
            $cfg,
            $parser,
            ' ' . $input,
            $expectedResult,
            $expectedIteratorPos === null ? null : $expectedIteratorPos + 1,
            $expectedValue,
            $expectedAttribute,
            $expectedAttributeType,
            $skipper
        );

        // if the parser does not require pre-skipping
        // we are done here
        if ($parser instanceof Char) {
            return;
        }

        // parsing should fail if no skipper defined
        // and skippable content is prepended to input
        $this->parserTest(
            $cfg,
            $parser,
            ' ' . $input,
            false
        );
    }

    /**
     * Create a formatted test description used on test failure.
     *
     * @param Traversable $input                parser input file
     * @param mixed $expectedValue              expected value to get parsed
     * @param string $expectedAttributeType     expected type of attribute
     * @param bool $expectedResult              expected result of parse() call
     * @param mixed $expectedAttribute          particular attribute expected
     * @param bool $result                      actual result of parse()
     * @param Parser $skipper                   skipper used
     * @param int $pos                          iterator position after parse()
     * @param mixed $attribute                  actual attribute after parse()
     * @param string $attributeType             actual type of attribute
     *
     * @return string                           formatted test description
     */
    public function getTestDescription(
        $parser,
        $iterator,
        $input,
        $expectedValue,
        string $expectedAttributeType = null,
        bool $expectedResult,
        $expectedAttribute,
        bool $result,
        Parser $skipper = null,
        int $pos,
        $attribute = 'n/a',
        $attributeType = 'n/a',
        $expectedIteratorPos = 'n/a'
    ) {
        $nextInput = '';
        $parser->try();
        for ($i = 0; $i < 20; $i++) {
            if ($iterator->valid()) {
                $nextInput .= $iterator->current();
                $iterator->next();
            }
        }
        $parser->reject();
        $expectedAttribute = $expectedAttribute !== null ?
            gettype($expectedAttribute) === 'object' ? get_class($expectedAttribute)
            : $expectedAttribute : 'n/a';

        return sprintf(
            "  Test Set:\n"
            . "    Input: %s\n"
            . "    Skipper: %s\n"
            . "    Expected Value: %s\n"
            . "    Expected Result: %s\n"
            . "    Expected Attribute: %s\n"
            . "    Expected Attribute Type: %s\n"
            . "    Expected Iterator Position: %s\n"
            . "  Results:\n"
            . "    Parser Result: %s\n"
            . "    Attribute: %s\n"
            . "    Attribute Type: %s\n"
            . "    Iterator Position: %s\n"
            . "    Next max 20 characters of input: %s\n",
            var_export($input, true),
            is_object($skipper) ? get_class($skipper) : 'none',
            var_export($expectedValue, true),
            var_export($expectedResult, true),
            print_r($expectedAttribute, true),
            $expectedAttributeType ?? 'n/a',
            $expectedIteratorPos ?? 'n/a',
            var_export($result, true),
            is_string($attribute) ? $attribute : var_export($attribute, true),
            $attributeType ?? 'n/a',
            $pos,
            var_export($nextInput, true)
        );
    }
}
