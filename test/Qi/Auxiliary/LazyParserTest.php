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
    protected function getParserConfig(string $parser, $class, $options)
    {
        return sprintf(
            "Test of %s:\n"
            . "  Setup:\n"
            . "    class: %s\n"
            . "    options: %s\n",
            $parser,
            $class,
            var_export($options, true)
        );
    }

    /** @dataProvider lazyParserDataProvider */
    public function testLazyParser(
        string $input,
        string $class,
        array $opt = [],
        bool $expectedResult = false,
        $expectedAttribute = null
    ) {

        $cfg = $this->getParserConfig(LazyParser::class, $class, $opt);
        $domain = $this->pm->get(Domain::class);
        $options[] = $domain;
        foreach ($opt as $value) {
            $options[] = $value;
        }
        $pf = new ParserFactory();

        $parser = $pf($this->pm, LazyParser::class, [ $class, $opt ]);

        if (! class_exists($class)) {
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
        $tests = [
            [ 'c', CharParser::class, ['c'], true, 'c' ],
            [ '123', IntParser::class, [null, 1,2], false ],
            [ '12 3', IntParser::class, [null, 1,2], true, 12 ],
            [ '12 3', 'IntParser', [null, 1,2] ],
        ];
        return $tests;
    }
}
