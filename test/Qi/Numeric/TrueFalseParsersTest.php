<?php

namespace Mxc\Test\Parsec\Qi\Numeric;

use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Numeric\TrueParser;
use Mxc\Parsec\Qi\Numeric\FalseParser;

class TrueFalseParsersTest extends ParserTestBed
{
    protected function getParserConfig(string $parser)
    {
        return sprintf(
            "Test of %s:\n",
            $parser
        );
    }

    /** @dataProvider trueFalseParsersDataProvider */
    public function testTrueFalseParsers(
        $class,
        $input,
        $expectedResult,
        $expectedAttribute = null
    ) {
        $cfg = $this->getParserConfig($class);
        $parser = $this->pm->build($class);

        $this->doTest(
            $cfg,
            $parser,
            $input,
            $expectedResult,
            null,
            $expectedAttribute
        );
    }

    public function trueFalseParsersDataProvider()
    {
        $tests = [
            [TrueParser::class, 'true', true, true],
            [TrueParser::class, 'false', false],
            [FalseParser::class, 'true', false],
            [FalseParser::class, 'false', true, false],
        ];

        return $tests;
    }
}
