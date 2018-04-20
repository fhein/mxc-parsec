<?php

namespace Mxc\Test\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Directive\OmitDirective;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Test\Parsec\Qi\Assets\MockPreSkipperMatchingAllButCaret;
use Mxc\Parsec\Qi\Unused;

class OmitDirectiveTest extends ParserTestBed
{
    protected function getParserConfig(string $directive)
    {
        return sprintf(
            "Test of %s:\n",
            $directive
        );
    }

    /** @dataProvider omitDirectiveDataProvider */
    public function testOmitDirective(
        string $source,
        bool $expectedResult,
        $expectedIteratorPos = null
    ) {
        $cfg = $this->getParserConfig(OmitDirective::class);

        $domain = $this->pm->get(Domain::class);
        $mock = new MockPreSkipperMatchingAllButCaret($domain);
        $directive = new OmitDirective($domain, $mock);

        $this->doTest(
            $cfg,                       // test configuration description
            $directive,                 // directive to test
            $source,                    // input
            $expectedResult,            // expected result
            null,                       // expected value (any)
            new Unused(),               // expected attribute
            null,                       // expected attribute type
            $expectedIteratorPos        // expected position of iterator after parsing
        );
    }

    public function omitDirectiveDataProvider()
    {
        $tests = [
            [ '^', false ],
            [ 'a', true ],
        ];
        return $tests;
    }
}
