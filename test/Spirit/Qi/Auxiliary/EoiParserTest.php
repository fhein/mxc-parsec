<?php

namespace Mxc\Test\Parsec\Qi\Auxiliary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Auxiliary\EoiParser;

class EoiParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    n/a\n",
            $parser
        );
    }

    public function testEoiParser()
    {

        $cfg = $this->getParserConfig(EoiParser::class);
        $parser = $this->pm->build(EoiParser::class);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            'abc',              // parser input
            false               // expected result
        );

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            '',                 // parser input
            true                // expected result
        );
    }
}
