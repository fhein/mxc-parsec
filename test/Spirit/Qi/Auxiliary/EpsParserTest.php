<?php

namespace Mxc\Test\Parsec\Qi\Auxiliary;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Auxiliary\EpsParser;

class EpsParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, $callable = null)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    Callable: %s\n",
            $parser,
            $callable ? var_export($callable, true) : 'n/a'
        );
    }

    public function testEpsParser()
    {
        $cfg = $this->getParserConfig(EpsParser::class);
        $uid = 'test';
        $parser = $this->pm->build(EpsParser::class, [ $uid ]);
        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            '',                 // parser input
            true                // expected result
        );

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            'abc',              // parser input
            true                // expected result
        );

        $callable = function () {
            return false;
        };

        $cfg = $this->getParserConfig(EpsParser::class, $callable);
        $parser = $this->pm->build(EpsParser::class, [ $uid, $callable ]);
        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            'abc',              // parser input
            false               // expected result
        );
    }
}
