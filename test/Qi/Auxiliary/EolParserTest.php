<?php

namespace Mxc\Test\Parsec\Qi\Auxiliary;

use Mxc\Test\Parsec\ParserTestBed;
use IntlChar;
use Mxc\Parsec\Qi\Auxiliary\EolParser;

class EolParserTest extends ParserTestBed
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

    /** @dataProvider eolDataProvider */
    public function testEolParser($input, $expectedResult)
    {

        $cfg = $this->getParserConfig(EolParser::class);
        $parser = $this->pm->build(EolParser::class);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult     // expected result
        );
    }

    public function eolDataProvider()
    {
        return [
            [ "\r\n", true],
            [ "\n\r", true ],
            [ "\n", true ],
            [ IntlChar::chr(0x0085), true ],
            [ IntlChar::chr(0x2028), true ],
            [ \IntlChar::chr(0x2029), true],
            [ 'abc', false],
        ];
    }
}
