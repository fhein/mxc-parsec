<?php

namespace Mxc\Test\Parsec;

use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\Char\CharClassParser;
use Mxc\Parsec\Qi\PreSkipper;
use PHPUnit\Framework\TestCase;
use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Exception\UnknownCastException;
use Mxc\Parsec\Qi\Unused;

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
            $this->skipper = $this->pm->build(CharClassParser::class, [ 'space' ]);
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
                return $this->pm->get(Unused::class);
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
        $expectedValue = null,
        $expectedAttribute = null,
        string $expectedAttributeType = null,
        Parser $skipper = null
    ) {
        $iterator = $parser->setSource($input);
        $result = $parser->parse($iterator, $expectedValue, $expectedAttributeType, $skipper);

        self::assertSame(
            $expectedResult,
            $result,
            sprintf(
                "%s\n%s\n\n  Unexpected result of parse(): %s. Expected: %s\n",
                $cfg,
                $this->getTestDescription(
                    $input,
                    $expectedValue,
                    $expectedAttributeType,
                    $expectedResult,
                    $expectedAttribute,
                    $result,
                    $skipper,
                    $iterator->key()
                ),
                var_export($result, true),
                var_export($expectedResult, true)
            )
        );
        $attribute = $parser->getAttribute();
        if ($expectedResult === true && $expectedValue !== null) {
            $attributeType = $this->getType($attribute);
            if ($expectedAttributeType !== null) {
                self::assertSame(
                    $expectedAttributeType,
                    $attributeType,
                    sprintf(
                        "%s\n%s\n\n  Unexpected attribute type: %s. Expected: %s\n",
                        $cfg,
                        $this->getTestDescription(
                            $input,
                            $expectedValue,
                            $expectedAttributeType,
                            $expectedResult,
                            $expectedAttribute,
                            $result,
                            $skipper,
                            $iterator->key(),
                            $attribute,
                            $attributeType
                        ),
                        $attributeType,
                        $expectedAttributeType
                    )
                );
            }

            self::assertSame(
                $this->getTypedExpectedValue($expectedAttributeType, $expectedValue),
                $attribute,
                sprintf(
                    "%s\n%s\n\n  Attribute mismatch: %s. Expected: %s\n",
                    $cfg,
                    $this->getTestDescription(
                        $input,
                        $expectedValue,
                        $expectedAttributeType,
                        $expectedResult,
                        $expectedAttribute,
                        $result,
                        $skipper,
                        $iterator->key(),
                        $attribute,
                        $attributeType
                    ),
                    var_export($attribute, true),
                    var_export($expectedAttribute, true)
                )
            );
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
     * @param string $cfg                   Text describing the parser and its configuration
     * @param Parser $parser                Parser to be tested
     * @param Traversable $input            Parser input
     * @param bool $expectedResult          Expected result of parse()
     * @param mixed $expectedValue          Expected value to get parsed
     * @param mixed $expectedAttribute      Particular value of attribute
     * @param string $expectedAttributeType Expected type of attribute
     */
    protected function doTest(
        string $cfg,
        Parser $parser,
        $input,
        bool $expectedResult,
        $expectedValue = null,
        $expectedAttribute = null,
        string $expectedAttributeType = null
    ) {
        if ($expectedAttributeType === 'null') {
            $attributeType = 'NULL';
        }

        // perform test with parameters as received
        $this->parserTest(
            $cfg,
            $parser,
            $input,
            $expectedResult,
            $expectedValue,
            $expectedAttribute,
            $expectedAttributeType
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
            $expectedValue,
            $expectedAttribute,
            $expectedAttributeType,
            $this->getSkipper()
        );

        // succeeding tests should also succeed if skipper is supplied
        // and skippable content is prepended to input
        $this->parserTest(
            $cfg,
            $parser,
            ' ' . $input,
            $expectedResult,
            $expectedValue,
            $expectedAttribute,
            $expectedAttributeType,
            $this->getSkipper()
        );

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
        $input,
        $expectedValue,
        string $expectedAttributeType = null,
        bool $expectedResult,
        $expectedAttribute,
        bool $result,
        Parser $skipper = null,
        int $pos,
        $attribute = 'n/a',
        $attributeType = 'n/a'
    ) {
        return sprintf(
            "  Test Set:\n"
            . "    Input: %s\n"
            . "    Skipper: %s\n"
            . "    Expected value: %s\n"
            . "    Expected attribute type: %s\n"
            . "    Expected result: %s\n"
            . "    Expected Attribute: %s\n\n"
            . "  Results:\n"
            . "    Parsing result: %s\n"
            . "    Attribute: %s\n"
            . "    Attribute type: %s\n"
            . "    Iterator position: %d",
            var_export($input, true),
            is_object($skipper) ? get_class($skipper) : 'none',
            var_export($expectedValue, true),
            $expectedAttributeType,
            var_export($expectedResult, true),
            gettype($expectedAttribute) === 'object' ? get_class($expectedAttribute) : $expectedAttribute,
            var_export($result, true),
            is_string($attribute) ? $attribute : var_export($attribute, true),
            $attributeType,
            $pos
        );
    }
}
