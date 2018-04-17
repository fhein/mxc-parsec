<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Attribute\Optional;
use Mxc\Parsec\Qi\Unused;

include __DIR__.'/../autoload.php';

$pm = new ParserManager();
$a = $pm->getFQCN();
foreach ($a as $n => $fqcn) {
    printf("% -25s: %s\n", $n, $fqcn);
}

$c1 = new Optional(new Unused());
$c2 = new Optional(new Unused());
assert($c1 === $c2);
assert($c1 == $c2);

class Parser
{
    protected $pm;
    protected $parser;

    public function __construct(ParserManager $pm)
    {
        $this->pm = $pm;
    }

    protected function createParser()
    {
        $rule1 = $this->pm->build(
            PlusOperator::class,
            [
                null,   // expected value
                null,   // attribute type
                null,   // skipper
                [
                    $this->pm->build(
                        DifferenceOperator::class,
                        [
                            null,   // expected value
                            null,   // attribute type
                            null,   // skipper
                            [
                                $this->pm->build(
                                    CharParser::class,
                                    [
                                        null,   // expected value
                                        null,   // attribute type
                                        null,   // skipper
                                        [
                                            false   // negate
                                        ]
                                    ],
                                    CharParser::class,
                                    [
                                        '^',    // expected value
                                        null,   // attribute type
                                        null,   // skipper
                                        [
                                            false   // negate
                                        ]
                                    ]
                                ),
                            ]
                        ]
                    )
                ]
            ]
        );

        $this->parser = $rule1;
    }
}
