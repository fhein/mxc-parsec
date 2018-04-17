<?php

namespace Mxc\Test\Parsec\Qi\Char;

use IntlChar;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Char\CharClassParser;
use Mxc\Parsec\Encoding\CharacterClassifier;

class CharClassParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, string $charClass, bool $negate = false)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    Char Class: %s\n"
            . "    Negate: %s\n",
            $parser,
            $charClass,
            $negate ? 'yes' : 'no'
        );
    }

    /** @dataProvider charClassDataProvider */
    public function testCharClassParser(
        $negate,
        $charClass,
        $input,
        $expectedResult,
        $expectedValue = null,
        $expectedAttribute = null
    ) {
        $cfg = $this->getParserConfig(CharClassParser::class, $charClass, $negate);
        $parser = $this->pm->build(CharClassParser::class, [ $charClass, $negate ]);

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            $expectedValue,     // expected value
            $expectedAttribute  // expected attribute value
        );
    }

    public function charClassDataProvider()
    {
        $charClasses =
        [
            // we do not test blank and space here
            // because they are tested via SkipperTest
            'alnum'     => 'azAZ09',
            'alpha'     => 'azAZ\xC5',
            'digit'     => '09',
            'xdigit'    => 'afAF09',
            'cntrl'     => '\x00',
            'graph'     => '\xFF',
            'lower'     => 'az',
            'upper'     => 'AZ',
            'print'     => "\xA0",
            'punct'     => "\xA1",
        ];

        $cp = new CharacterClassifier();

        $tests = [];

        foreach ($charClasses as $cc => $input) {
            $method = 'is'.$cc;
            $sl = strlen($input);
            for ($l = 0; $l < $sl; $l++) {
                $ch = $input[$l];
                if ($cp->isvalid($ch)) {
                    if (IntlChar::$method($ch)) {
                        $tests[] = [ false, $cc, $ch, true, $ch, $ch ];
                        $tests[] = [ false, $cc, $ch, true, null, $ch ];
                        $tests[] = [ false, $cc, $ch, false, $ch === 'a' ? 'b' : 'a' ];
                        $tests[] = [ true, $cc, $ch, false ];
                    } else {
                        $tests[] = [ false, $cc, $ch, false ];
                        $tests[] = [ false, $cc, $ch, false ];
                        $tests[] = [ false, $cc, $ch, false, $ch === 'a' ? 'b' : 'a' ];
                        $tests[] = [ true, $cc, $ch, true, $ch, $ch ];
                        $tests[] = [ true, $cc, $ch, true, null, $ch ];
                        $tests[] = [ false, $cc, $ch, false, $ch === 'a' ? 'b' : 'a' ];
                    }
                }
            }
        }
        return $tests;
    }
}
