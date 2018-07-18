<?php

namespace Mxc\Test\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Directive\NoCaseDirective;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Char\CharSetParser;

class NoCaseDirectiveTest extends ParserTestBed
{
    protected function getParserConfig(string $directive)
    {
        return sprintf(
            "Test of %s:\n",
            $directive
        );
    }

    /** @dataProvider noCaseDirectiveDataProvider */
    public function testNoCaseDirective(
        string $source,
        bool $expectedResult,
        $expectedValue = null,
        $expectedAttribute = null,
        $expectedIteratorPos = null
    ) {
        $cfg = $this->getParserConfig(NoCaseDirective::class);
        $uid = 'test';

        $domain = $this->pm->get(Domain::class);
        $csp = new CharSetParser($domain, $uid, 'a-z');
        $directive = new NoCaseDirective($domain, $uid, $csp);

        $this->doTest(
            $cfg,                       // test configuration description
            $directive,                 // directive to test
            $source,                    // input
            $expectedResult,            // expected result
            $expectedValue,             // expected value
            $expectedAttribute,         // expected attribute
            null,                       // expected attribute type
            $expectedIteratorPos        // expected position of iterator after parsing
        );
    }

    public function noCaseDirectiveDataProvider()
    {
        $tests = [
            [ '^', false],
            [ 'A', true,  'A' ],
            [ 'a', true, 'a' ],
        ];
        return $tests;
    }
}
