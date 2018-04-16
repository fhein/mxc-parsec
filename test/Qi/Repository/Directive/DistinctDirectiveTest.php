<?php

namespace Mxc\Test\Parsec\Qi\Repository\Directive;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\String\StringParser;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Repository\Directive\DistinctDirective;
use Mxc\Parsec\Domain;

class DistinctDirectiveTest extends ParserTestBed
{
    protected function getParserConfig(string $directive)
    {
        return sprintf(
            "Test of %s:\n",
            $directive
        );
    }

    /** @dataProvider distinctDirectiveDataProvider */
    public function testDistinctDirective(
        string $source,
        bool $expectedResult,
        string $expectedAttribute = null
    ) {
            $cfg = $this->getParserConfig(DistinctDirective::class);

            $domain = $this->pm->get(Domain::class);
            $string = new StringParser($domain, 'Description');
            $tail = new CharSetParser($domain, 'a-z_');
            $directive = new DistinctDirective($domain, $tail, $string);

            $this->doTest(
                $cfg,                   // test configuration description
                $directive,             // directive to test
                $source,                // input
                $expectedResult,        // expected result
                null,                   // expected value (any)
                $expectedAttribute,     // expected attribute
                null,                   // expected attribute type
                null                    // expected position of iterator after parsing
            );
    }

    public function distinctDirectiveDataProvider()
    {
        $tests = [
            [ 'Descriptiona', false ],
            [ '   Descriptiona', false ],
            [ 'Description', true, 'Description' ],
            [ '   Description', true, 'Description' ],
            [ 'Description0', true, 'Description' ],
            [ '   Description0', true, 'Description' ],
            [ 'Description 0', true, 'Description' ],
            [ '   Description 0', true, 'Description' ],
            [ 'Description a', true, 'Description' ],
            [ '   Description a', true, 'Description' ],
        ];
        return $tests;
    }
}
