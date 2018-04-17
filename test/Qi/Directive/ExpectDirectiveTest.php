<?php

namespace Mxc\Test\Parsec\Qi\Char;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Directive\ExpectDirective;
use Mxc\Parsec\Qi\PrimitiveParser;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Directive\Detail\ExpectationFailedException;
use Mxc\Parsec\Qi\PreSkipper;

class ExpectDirectiveTest extends ParserTestBed
{
    protected function getParserConfig(string $directive, $mockBase, $mockMember, $mockResult, $exception)
    {
        return sprintf(
            "Test of %s:\n"
            . "    Mock Base: %s"
            . "    Mock Member: %s"
            . "    Mock Result: %s"
            . "    Expected exception: %s",
            $directive,
            $mockBase,
            $mockMember,
            var_export($mockResult, true),
            $exception ?? 'n/a'
        );
    }

    /** @dataProvider expectDirectiveDataProvider */
    public function testExpectDirective(
        string $mockBase,
        string $mockMember,
        bool $mockResult,
        string $exception = null
    ) {
        $cfg = $this->getParserConfig(ExpectDirective::class, $mockBase, $mockMember, $mockResult, $exception);
        $mock = $this->getMockBuilder($mockBase)
            ->setMethods([$mockMember])
            ->setConstructorArgs([ $this->pm->get(Domain::class)])
            ->getMock();

        $mock->expects($this->any())
            ->method($mockMember)
            ->willReturn($mockResult);

        $directive = $this->pm->build(ExpectDirective::class, [ $mock ]);

        if ($exception !== null) {
            self::expectException($exception);
        }
        $this->doTest(
            $cfg,               // test configuration description
            $directive,         // directive to test
            '',                 // input
            true                // expected result
        );
    }

    public function expectDirectiveDataProvider()
    {
        $tests = [
            [ PrimitiveParser::class, 'doParse', true ],
            [ PrimitiveParser::class, 'parse', false, ExpectationFailedException::class ],
            [ PreSkipper::class, 'doParse', true ],
            [ PreSkipper::class, 'parse', false, ExpectationFailedException::class ],
        ];
        return $tests;
    }
}
