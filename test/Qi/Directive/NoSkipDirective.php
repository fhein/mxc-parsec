<?php

namespace Mxc\Test\Parsec\Qi\Char;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Directive\NoSkipDirective;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Test\Parsec\Qi\Assets\MockPreSkipperMatchingAllButCaret;

class NoSkipDirectiveTest extends ParserTestBed
{
    protected function getParserConfig(string $directive)
    {
        return sprintf(
            "Test of %s:\n",
            $directive
        );
    }

    /** @dataProvider noSkipDirectiveDataProvider */
    public function testNoSkipDirective(
        string $source,
        bool $expectedResult,
        $expectedValue = null,
        $expectedAttribute = null,
        $expectedIteratorPos = null
    ) {
        $cfg = $this->getParserConfig(NoSkipDirective::class);

        $domain = $this->pm->get(Domain::class);
        $mock = new MockPreSkipperMatchingAllButCaret($domain);
        $directive = new NoSkipDirective($domain, $mock);
        self::assertInstanceOf(NoSkipDirective::class, $directive);

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

    public function noSkipDirectiveDataProvider()
    {
        $tests = [
            [ '^', false],
            [ 'A', true, 'a', 'A' ],
            [ 'a', false, 'B' ],
        ];
        return $tests;
    }
}
