<?php

namespace Mxc\Test\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\Auxiliary\LitParser;
use Mxc\Parsec\Qi\Auxiliary\RuleReference;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Char\SpaceParser;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\NonTerminal\Grammar;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\ListOperator;
use Mxc\Parsec\Qi\Operator\SequenceOperator;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\Directive\LexemeDirective;

class GrammarTest extends ParserTestBed
{
    protected function getParserConfig(string $parser, string $name, string $startRule)
    {

        return sprintf(
            "Test of %s:\n"
            . "    Name: %s\n"
            . "    Start Rule: %s\n",
            $parser,
            $name,
            $startRule
        );
    }

    public function getGrammar()
    {
        $identifier = $this->pm->build(
            LexemeDirective::class,
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
                                PlusOperator::class,
                                [
                                    $this->pm->build(
                                        CharSetParser::class,
                                        [
                                            'A-Za-z0-9_',
                                        ]
                                    )

                                ]
                            )
                        ]
                    ]
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

    /** @dataProvider grammarDataProvider */
    public function testGrammar(string $input, bool $expectedResult, array $expectedAttribute)
    {

        $domain = $this->pm->get(Domain::class);

        $grammar = $this->getGrammar();
        $cfg = $this->getParserConfig(Grammar::class, $grammar->getName(), $input);
        $this->doTest(
            $cfg,                       // test configuration description
            $grammar,                   // operator to test
            $input,                     // input
            $expectedResult,            // expected result
            null,                       // expected value
            $expectedAttribute          // expectedAttribute
        );
    }

    public function grammarDataProvider()
    {
        $tests = [
            ['Frank wer das liest', true, ['Frank']],
            ['Frank,', true, ['Frank']],
            ['Frank, wer das liest', true, ['Frank', 'wer']],
            ['Frank, Gabi wer das liest', true, ['Frank', 'Gabi']],
            ['Frank, Gabi, wer das liest', true, ['Frank', 'Gabi', 'wer']],
        ];
        return $tests;
    }
}
