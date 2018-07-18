<?php

namespace Mxc\Test\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Directive\MatchesDirective;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Test\Parsec\Qi\Assets\MockPreSkipperMatchingAllButCaret;

class MatchesDirectiveTest extends ParserTestBed
{
    protected function getParserConfig(string $directive)
    {
        return sprintf(
            "Test of %s:\n",
            $directive
        );
    }

    /** @dataProvider matchesDirectiveDataProvider */
    public function testMatchesDirective(
        string $source,
        bool $expectedResult,
        bool $expectedAttribute = null,
        $expectedIteratorPos = null
    ) {
        $cfg = $this->getParserConfig(MatchesDirective::class);
        $uid = 'test';

        $domain = $this->pm->get(Domain::class);
        $mock = new MockPreSkipperMatchingAllButCaret($domain);
        $directive = new MatchesDirective($domain, $uid, $mock);

        $this->doTest(
            $cfg,                       // test configuration description
            $directive,                 // directive to test
            $source,                    // input
            $expectedResult,            // expected result
            null,                       // expected value (any)
            $expectedAttribute,         // expected attribute
            null,                       // expected attribute type
            $expectedIteratorPos        // expected position of iterator after parsing
        );
    }

    public function matchesDirectiveDataProvider()
    {
        $tests = [
            [ '^', true, false ],
            [ 'a', true, true ],
        ];
        return $tests;
    }
}
