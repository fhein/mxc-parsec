<?php

namespace Mxc\Test\Parsec\Qi\Char;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Directive\LexemeDirective;
use Mxc\Parsec\Domain;
use Mxc\Test\Parsec\Qi\Assets\MockPreSkipperMatchNonSkippable;
use Mxc\Parsec\Qi\Char\CharClassParser;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Test\Parsec\Qi\Assets\MockPreSkipperMatchingAllButCaret;

class LexemeDirectiveTest extends ParserTestBed
{
    protected function getParserConfig(string $directive)
    {
        return sprintf(
            "Test of %s:\n",
            $directive
        );
    }

    /** @dataProvider lexemeDirectiveDataProvider */
    public function testLexemeDirective(
        string $source,
        bool $expectedResult,
        string $expectedAttribute = null,
        $expectedIteratorPos = null
    ) {
        $cfg = $this->getParserConfig(LexemeDirective::class);

        $domain = $this->pm->get(Domain::class);
        $mock = new MockPreSkipperMatchingAllButCaret($domain);
        $directive = new LexemeDirective($domain, $mock);
        self::assertInstanceOf(LexemeDirective::class, $directive);

        $this->doTest(
            $cfg,                   // test configuration description
            $directive,             // directive to test
            $source,                // input
            $expectedResult,        // expected result
            null,                   // expected value (any)
            $expectedAttribute,     // expected attribute
            null,                   // expected attribute type
            $expectedIteratorPos    // expected position of iterator after parsing
        );
    }

    public function lexemeDirectiveDataProvider()
    {
        $tests = [
            [ 'abcdef^', true, 'abcdef' ],
            [ '   abcdef^', true, 'abcdef' ],
            [ 'abc def^', true, 'abc def' ],
            [ 'abc   def^', true, 'abc   def'],
            [ '^', false ],
            [ '', false ],
        ];
        return $tests;
    }
}
