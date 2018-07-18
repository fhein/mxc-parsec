<?php

namespace Mxc\Test\Parsec\Qi\Auxiliary;

use function array_merge;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Auxiliary\LazyParser;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Numeric\IntParser;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Service\ParserFactory;

class LazyParserTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, $pd)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    class: %s\n"
            . "    options: %s\n",
            $parser,
            var_export($pd[0], true),
            var_export($pd[1], true)
        );
    }

    /** @dataProvider lazyParserDataProvider */
    public function testLazyParser(
        string $input,
        array $parserDefinition,
        bool $expectedResult = false,
        $expectedAttribute = null
    ) {

        $cfg = $this->getParserConfig(LazyParser::class, $parserDefinition);
        $domain = $this->pm->get(Domain::class);
        $options[] = $domain;
        $pf = new ParserFactory();
        $uid = 'test';

        $parser = $pf($this->pm, LazyParser::class, [ $uid, $parserDefinition ]);

        if (! class_exists($parserDefinition[0])) {
            self::expectException(InvalidArgumentException::class);
        }

        $this->doTest(
            $cfg,               // test configuration description
            $parser,            // parser to test
            $input,             // parser input
            $expectedResult,    // expected result
            null,               // expected value (any)
            $expectedAttribute  // expected attribute
        );
    }

    public function lazyParserDataProvider()
    {
        $uid = 'test';
        $tests = [
            [ 'c', [ CharParser::class, [$uid, 'c']], true, 'c' ],
            [ '123', [ IntParser::class, [$uid, null, 1,2]], false ],
            [ '12 3', [ IntParser::class, [$uid, null, 1,2]], true, 12 ],
            [ '12 3', [ 'IntParser', [$uid, null, 1,2]] ],
        ];
        return $tests;
    }
}
