<?php

// todo: This needs to be reworked because of new rule reference semantics

namespace Mxc\Test\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\Auxiliary\LitParser;
use Mxc\Parsec\Qi\Auxiliary\RuleReference;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\NonTerminal\Grammar;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Qi\Operator\ListOperator;
use Mxc\Parsec\Qi\Operator\SequenceOperator;
use Mxc\Test\Parsec\ParserTestBed;
use Mxc\Parsec\Qi\Operator\KleenePlusOperator;
use Mxc\Parsec\Qi\Directive\LexemeDirective;

class Grammar extends ParserTestBed
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
                'test',
                $this->pm->build(
                    SequenceOperator::class,
                    [
                        'test',
                        [
                            $this->pm->build(
                                CharSetParser::class,
                                [
                                    'test',
                                    'A-Za-z_',
                                ]
                            ),
                            $this->pm->build(
                                KleenePlusOperator::class,
                                [
                                    'test',
                                    $this->pm->build(
                                        CharSetParser::class,
                                        [
                                            'test',
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
                'test',
                'identifier',
                $identifier,
                'string'
            ]
        );

        $identifierList = $this->pm->build(
            ListOperator::class,
            [
                'test',
                $this->pm->build(
                    RuleReference::class,
                    [
                        'test',
                        'identifier'
                    ]
                ),
                $this->pm->build(
                    LitParser::class,
                    [
                        'test',
                        ','
                    ]
                )
            ]
        );

        $rule2 = $this->pm->build(
            Rule::class,
            [
                'test',
                'identifier_list',
                $identifierList,
                'array'
            ]
        );

        return $this->pm->build(
            Grammar::class,
            [
                'test',
                'grammar',
                [ 'test', $rule1, $rule2 ],
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
