<?php

namespace Mxc\Test\Parsec\Qi\Repository\Auxiliary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Repository\Auxiliary\AdvanceParser;
use Mxc\Parsec\Service\ParserFactory;

class AdvanceParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  No setup.\n",
            $parser
        );
    }

    /** @dataProvider advanceParserDataProvider */
    public function testAdvanceParser(
        string $input,
        int $advance,
        bool $expectedResult = false,
        int $expectedIteratorPos = null
    ) {
        $cfg = $this->getParserConfig(AdvanceParser::class);
        $uid = 'test';

        $pf = new ParserFactory();
        $parser = $pf($this->pm, AdvanceParser::class, [ $uid, $advance ]);

        $this->doTest(
            $cfg,                   // test configuration description
            $parser,                // parser to test
            $input,                 // parser input
            $expectedResult,        // expected result
            null,                   // expected value (any)
            null,                   // expected attribute
            null,                   // expected attribute type
            $expectedIteratorPos    // expected iterator pos
        );
    }

    public function advanceParserDataProvider()
    {
        $tests = [
            ['ABCDEFGHIJKLMN', 4, true, 4 ],
            ['ABC', 4, false ],
        ];
        return $tests;
    }
}
