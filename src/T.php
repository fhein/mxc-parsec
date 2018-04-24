<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Attribute\Optional;
use Mxc\Parsec\Attribute\Unused;
use Mxc\Parsec\Qi\Operator\SequenceOperator;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Char\SpaceParser;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Qi\Auxiliary\RuleReference;
use Mxc\Parsec\Qi\Auxiliary\LitParser;
use Mxc\Parsec\Qi\Operator\ListOperator;
use Mxc\Parsec\Qi\NonTerminal\Grammar;

include __DIR__.'/../autoload.php';

$pm = new ParserManager();
// $a = $pm->getFQCN();
// foreach ($a as $n => $fqcn) {
//     printf("% -25s: %s\n", $n, $fqcn);
// }

// $c1 = new Optional(new Unused());
// $c2 = new Optional(new Unused());
// assert($c1 === $c2);
// assert($c1 == $c2);

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
                            $this->pm->build(
                                CharParser::class,
                                []
                            ),
                            $this->pm->build(
                                CharParser::class,
                                [
                                    '^',    // expected value
                                    false   // negate
                                ]
                            ),
                        ]
                    )
                ]
            ]
        );

        $this->parser = $rule1;
    }
}

class GrammarContainer extends Parser
{
    public function createParser()
    {
        $identifier = $this->pm->build(
            DifferenceOperator::class,
            [
                $this->pm->build(
                    SequenceOperator::class,
                    [
                        [
                            $this->pm->build(
                                CharSetParser::class,
                                [
                                    'A-Za-z_',
                                ]
                            ),
                            $this->pm->build(
                                CharSetParser::class,
                                [
                                    'A-Za-z0-9_',
                                ]
                            )
                        ]
                    ]
                ),
                $this->pm->build(
                    SpaceParser::class,
                    []
                )
            ]
        );

        $rule1 = $this->pm->build(
            Rule::class,
            [
                'identifier',
                $identifier,
                'string'
            ]
        );

        $identifierList = $this->pm->build(
            ListOperator::class,
            [
                $this->pm->build(
                    RuleReference::class,
                    [
                        'identifier'
                    ]
                ),
                $this->pm->build(
                    LitParser::class,
                    [
                        ','
                    ]
                )
            ]
        );

        $rule2 = $this->pm->build(
            Rule::class,
            [
                'identifier_list',
                $identifierList,
                'array'
            ]
        );

        return $this->pm->build(
            Grammar::class,
            [
                'grammar',
                [ $rule1, $rule2 ],
                'identifier_list'
            ]
        );
    }
}

$grammar = new GrammarContainer($pm);
$grammar = $grammar->createParser();

$grammar->setSource('Frank');
