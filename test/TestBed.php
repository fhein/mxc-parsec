<?php

namespace Mxc\Test\Parsec;

use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\UnusedSkipper;
use Mxc\Parsec\Domain;

class TestBed
{
    protected $parser;
    protected $skipper;

    public function __construct(Parser $parser, ... $args)
    {
        $this->parser = $parser;
        $this->skipper = new UnusedSkipper();
    }

    // we forward all unknown members to our parser
    public function __call($method, $args)
    {
        if (method_exists($this->parser, $method)) {
            return call_user_func_array([$this->parser, $method], $args);
        }
        return false;
    }

    public function test(
        string $input,
        $expectedValue = null,
        $attributeType = null,
        $skipper = null,
        $expectedAttribute = null,
        $expectedResult = null,
        $policy = null
    ) {
        $skipper = $skipper?: $this->skipper;

        $iterator = $this->parser->setSource($input);
        $result['result']  = $this->parser->parseImpl($iterator, $expectedValue, $attributeType, $skipper);
        if ($result['result'] === true) {
            $result['attribute'] = $this->parser->getAttribute();
            $result['attribute_type'] = $this->parser->getAttributeType();
        }
        $verbose = $this->getVerboseResult(
            $input,
            $expectedValue,
            $attributeType,
            $expectedResult,
            $expectedAttribute,
            get_class($skipper) !== UnusedSkipper::class,
            $policy,
            $result
        );

        return $result;
    }

    public function getVerboseResult(
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
            . "Expected value: %s\n"
            . "Attribute type: %s\n"
            . "Expected result: %s\n"
            . "Expected Attribute: %s\n\n"
            . "Results:\n"
            . "Parsing result: %s\n"
            . "Attribute: %s\n"
            . "Attribute Type: %s",
            $input,
            var_export($expectedValue, true),
            $attributeType,
            var_export($expectedResult, true),
            gettype($expectedAttribute) === 'object' ? get_class($expectedAttribute) : $expectedAttribute,
            var_export($result['result'], true),
            var_export($result['attribute'], true),
            $result['attribute_type']
        );
    }
}
