<?php

namespace Mxc\Test\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Directive\NoCaseDirective;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Test\Parsec\Qi\Assets\MockPreSkipperMatchingAllButCaret;

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

        $domain = $this->pm->get(Domain::class);
        $mock = new MockPreSkipperMatchingAllButCaret($domain);
        $directive = new NoCaseDirective($domain, $mock);

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
            [ 'A', true, 'a', 'A' ],
            [ 'a', false, 'B' ],
        ];
        return $tests;
    }
}
