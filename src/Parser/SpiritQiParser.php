<?php

namespace Mxc\Parsec\Parser;

use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Qi\Directive\LexemeDirective;
use Mxc\Parsec\Qi\Directive\NoSkipDirective;
use Mxc\Parsec\Qi\Directive\NoCaseDirective;
use Mxc\Parsec\Qi\Directive\OmitDirective;
use Mxc\Parsec\Qi\Directive\MatchesDirective;
use Mxc\Parsec\Qi\Directive\RawDirective;
use Mxc\Parsec\Qi\Directive\HoldDirective;
use Mxc\Parsec\Qi\Directive\RepeatDirective;
use Mxc\Parsec\Qi\Directive\SkipDirective;
use Mxc\Parsec\Qi\Operator\SequenceOperator;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\ListOperator;
use Mxc\Parsec\Qi\Operator\OptionalOperator;
use Mxc\Parsec\Qi\Operator\NotPredicate;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\Operator\KleeneOperator;
use Mxc\Parsec\Qi\Operator\AndPredicate;
use Mxc\Parsec\Qi\Operator\ExpectOperator;
use Mxc\Parsec\Qi\Operator\PermutationOperator;
use Mxc\Parsec\Qi\Operator\AlternativeOperator;
use Mxc\Parsec\Qi\Numeric\BinaryParser;
use Mxc\Parsec\Qi\Numeric\OctParser;
use Mxc\Parsec\Qi\Numeric\HexParser;
use Mxc\Parsec\Qi\Numeric\UShortParser;
use Mxc\Parsec\Qi\Numeric\UIntParser;
use Mxc\Parsec\Qi\Numeric\IntParser;
use Mxc\Parsec\Qi\Auxiliary\EpsParser;
use Mxc\Parsec\Qi\Auxiliary\LazyParser;
use Mxc\Parsec\Qi\Auxiliary\AttrParser;
use Mxc\Parsec\Qi\Binary\ByteParser;
use Mxc\Parsec\Qi\Binary\WordParser;
use Mxc\Parsec\Qi\Binary\BigWordParser;
use Mxc\Parsec\Qi\Binary\LittleWordParser;
use Mxc\Parsec\Qi\Binary\QWordParser;
use Mxc\Parsec\Qi\Binary\BigQWordParser;
use Mxc\Parsec\Qi\Binary\LittleQWordParser;
use Mxc\Parsec\Qi\Binary\BinFloatParser;
use Mxc\Parsec\Qi\Binary\BigBinFloatParser;
use Mxc\Parsec\Qi\Binary\LittleBinFloatParser;
use Mxc\Parsec\Qi\Binary\BinDoubleParser;
use Mxc\Parsec\Qi\Binary\BigBinDoubleParser;
use Mxc\Parsec\Qi\Binary\LittleBinDoubleParser;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Qi\String\StringParser;
use Mxc\Parsec\Qi\Auxiliary\EolParser;
use Mxc\Parsec\Qi\Auxiliary\EoiParser;
use Mxc\Parsec\Qi\Char\AlnumParser;
use Mxc\Parsec\Qi\Char\AlphaParser;
use Mxc\Parsec\Qi\Char\BlankParser;
use Mxc\Parsec\Qi\Char\CntrlParser;
use Mxc\Parsec\Qi\Char\DigitParser;
use Mxc\Parsec\Qi\Char\GraphParser;
use Mxc\Parsec\Qi\Char\PrintParser;
use Mxc\Parsec\Qi\Char\PunctParser;
use Mxc\Parsec\Qi\Char\SpaceParser;
use Mxc\Parsec\Qi\Char\XDigitParser;
use Mxc\Parsec\Qi\Char\LowerParser;
use Mxc\Parsec\Qi\Char\UpperParser;
use Mxc\Parsec\Qi\Numeric\BoolParser;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\Numeric\FloatParser;
use Mxc\Parsec\Qi\Numeric\DoubleParser;
use Mxc\Parsec\Qi\Numeric\LongDoubleParser;
use Mxc\Parsec\Qi\Numeric\ULongParser;
use Mxc\Parsec\Qi\Numeric\ULongLongParser;
use Mxc\Parsec\Qi\Numeric\ShortParser;
use Mxc\Parsec\Qi\Numeric\LongParser;
use Mxc\Parsec\Qi\Numeric\LongLongParser;
use Mxc\Parsec\Qi\Repository\Directive\DistinctDirective;
use Mxc\Parsec\Qi\Operator\SequentialOrOperator;
use Mxc\Parsec\Qi\Directive\AsStringDirective;
use Mxc\Parsec\Qi\Auxiliary\LitParser;
use Mxc\Parsec\Qi\Repository\Auxiliary\AdvanceParser;
use Mxc\Parsec\Qi\Numeric\TrueParser;
use Mxc\Parsec\Qi\Numeric\FalseParser;

class SpiritQiParser
{
    protected $pm;
    public $rules;

    public function __construct(ParserManager $pm, string $source)
    {
        $this->pm = $pm;

        $backslash = "\\";
        $escaped_u = "\\U";
        $escaped_x = "\\X";
        $escaped_single_quote = "\\\'";
        $escaped_quote = "\\\"";
        $escaped_question_mark = "\\\?";
        $escaped_backslash = "\\\\";
        $escaped_a = "\\\a";
        $escaped_b = "\\\b";
        $escaped_f = "\\\f";
        $escaped_n = "\\\n";
        $escaped_r = "\\\r";
        $escaped_t = "\\\t";
        $escaped_v = "\\\v";

//         // ****!**** BEGIN

        $this->rules = [
            'directives' => [
                SymbolsParser::class, [
                    "lexeme" => LexemeDirective::class,
                    "no_skip" => NoSkipDirective::class,
                    "no_case" => NoCaseDirective::class,
                    "omit" => OmitDirective::class,
                    "matches" => MatchesDirective::class,
                    "as_string" => AsStringDirective::class,
                    "raw" => RawDirective::class,
                    "skip" => SkipDirective::class,
                    "hold" => HoldDirective::class,
                    "repeat" => RepeatDirective::class,
                    //   "as" => AsDirective::class,
                    //   "as_wstring" => as_wstring_directive,
                    // directives from repository
                    //  "confix" => token_type::confix_directive,
                    //  "kwd" => token_type::keyword_directive,
                    //  "ikwd" => token_type::ignore_case_keyword_directive,
                    //  "seek" => token_type::seek_directive,
                ],
            ],
            'distinct_directive' => [
                SymbolsParser::class, [
                    'distinct' => DistinctDirective::class,
                ],
            ],
            'prec_3_op' => [
                SymbolsParser::class, [
                    "-" => OptionalOperator::class,
                    "!" => NotPredicate::class,
                    "+" => PlusOperator::class,
                    "*" => KleeneOperator::class,
                    "&" => AndPredicate::class,
                ],
            ],
            'prec_5_op' => [
                SymbolsParser::class, [
                    '%' => ListOperator::class,
                ],
            ],
            'prec_6_op' => [
                SymbolsParser::class, [
                    '-' => DifferenceOperator::class,
                ],
            ],
            'prec_7_op' => [
                SymbolsParser::class, [
                    '>>' => SequenceOperator::class,
                ],
            ],
            'prec_8_op' => [
                SymbolsParser::class, [
                    '>' => ExpectOperator::class,
                ],
            ],
            'prec_11_op' => [
                SymbolsParser::class, [
                    '^' => PermutationOperator::class,
                ],
            ],
            'prec_12_op' => [
                SymbolsParser::class, [
                    '|' => AlternativeOperator::class,
                ],
            ],
            'prec_14_op' => [
                SymbolsParser::class, [
                    '||' => SequentialOrOperator::class,
                ],
            ],
            'prec_15_op' => [
                SymbolsParser::class, [
                    '=' => Rule::class,
                    '%=' => Rule::class,
                ],
            ],
            'escaped_chars' => [
                SymbolsParser::class, [
                    'escaped_u' => $escaped_u,
                    'escaped_x' => $escaped_x,
                    'escaped_single_quote' => $escaped_single_quote,
                    'escaped_quote' => $escaped_quote,
                    'escaped_question_mark' => $escaped_question_mark,
                    'escaped_backslash' => $escaped_backslash,
                    'escaped_a' => $escaped_a,
                    'escaped_b' => $escaped_b,
                    'escaped_f' => $escaped_f,
                    'escaped_n' => $escaped_n,
                    'escaped_r' => $escaped_r,
                    'escaped_t' => $escaped_t,
                    'escaped_v' => $escaped_v,
                ]
            ],
            'parsers' => [
                SymbolsParser::class, [
                    "float_" => FloatParser::class,
                    "double_" => DoubleParser::class,
                    "long_double" => LongDoubleParser::class,
                    "bin" => BinaryParser::class,
                    "oct" => OctParser::class,
                    "hex" => HexParser::class,
                    "ushort_" => UShortParser::class,
                    "ulong_" => ULongParser::class,
                    "uint_" => UIntParser::class,
                    "ulong_long" => ULongLongParser::class,
                    "short_" => ShortParser::class,
                    "long_" => LongParser::class,
                    "int_" => IntParser::class,
                    "long_long" => LongLongParser::class,
                    "eps" => EpsParser::class,
                    "lazy" => LazyParser::class,
                    "attr" => AttrParser::class,
                    "byte_" => ByteParser::class,
                    "word" => WordParser::class,
                    "big_word" => BigWordParser::class,
                    "little_word" => LittleWordParser::class,
                    "qword" => QWordParser::class,
                    "big_qword" => BigQWordParser::class,
                    "little_qword" => LittleQWordParser::class,
                    "bin_float" => BinFloatParser::class,
                    "big_bin_float" => BigBinFloatParser::class,
                    "little_bin_float" => LittleBinFloatParser::class,
                    "bin_double" => BinDoubleParser::class,
                    "big_bin_double" => BigBinDoubleParser::class,
                    "little_bin_double" => LittleBinDoubleParser::class,
                    "char_" => CharParser::class,
                    "string" => StringParser::class,
                    "lit" => LitParser::class,
                    "advance" => AdvanceParser::class,
                    "eol" => EolParser::class,
                    "eoi" => EoiParser::class,
                    //        "auto_" => AutoParser::class, @todo: Necessary?
                    "alnum" => AlnumParser::class,
                    "alpha" => AlphaParser::class,
                    "blank" => BlankParser::class,
                    "cntrl" => CntrlParser::class,
                    "digit" => DigitParser::class,
                    "graph" => GraphParser::class,
                    "print" => PrintParser::class,
                    "punct" => PunctParser::class,
                    "space" => SpaceParser::class,
                    "xdigit" => XDigitParser::class,
                    "lower" => LowerParser::class,
                    "upper" => UpperParser::class,
                    "bool_" => BoolParser::class,
                    "true_" => TrueParser::class,
                    "false_" => FalseParser::class,
                ],
            ],
            // start = +(symboltable | assignment | symboltable_definition);
            'start' => [
                PlusOperator::class, [
                    [AlternativeOperator::class, [
                        'symboltable',
                        'assignment',
                        'symboltable_definition',
                    ]],
                ],
            ],
            // precedence level 15 (lowest): assignment
            // assignment = id >> rule_operation > lit(';');
            'assignment' => [[
                ExpectOperator::class, [
                    [SequenceOperator::class, [
                        'id',
                        'rule_operation'
                    ]],
                ]
            ],
            // rule_operation = prec_15_op > prec_14_expr;
            'rule_operation' => [
                ExpectOperator::class, [
                    'prec_15_op',
                    'prec_14_expr',
                ],
            ],

            // symboltable = identifier >> lit('.') >> lit('add') >> +symbol >> lit(';')
            'symbol_table' => [
                SequenceOperator::class, [
                    [LitParser::class, ['.add']],
                    [PlusOperator::class, [
                        'symbol',
                    ]],
                    [LitParser::class, [';']],
                ],
            ],
            // name = (lit('"') >> *(char_ - '"') >> '"') | identifier;
            'name' => [
                AlternativeOperator::class, [
                    [SequenceOperator::class, [
                        [LitParser::class, ['"']],
                        [KleeneOperator::class, [
                            [DifferenceOperator::class, [
                                [CharParser::class, []],
                                [LitParser::class, ['"']],
                            ]],
                        ]],
                        [LitParser::class, ['"']],
                    ]],
                ],
                'identifier',
            ],

            // value = *(char_ - ')')
            'value' => [
                KleeneOperator::class, [
                    [DifferenceOperator::class, [
                        [CharParser::class, []],
                        [LitParser::class,  [')']],
                    ]],
                ],
            ],
            // symbol = '(' >> name >> lit(',') >> value > ')';
            'symbol' => [
                ExpectOperator::class, [
                    [SequenceOperator::class, [
                        [LitParser::class, ['(']],
                        'name',
                        [LitParser::class, [',']],
                        'value',
                    ]],
                    [LitParser::class, [')']],
                ],
            ],

            // symbol_type = *(char_ - '>') >> attr(token_type::op_symboltype);
            'symbol_type' => [
                SequenceOperator::class, [
                    [KleeneOperator::class, [
                        [DifferenceOperator::class, [
                            [CharParser::class, []],
                            [LitParser::class, ['>']],
                        ]],
                    ]],
                    [AttrParser::class, ['symboltype']],
                ],
            ],

            // symdef_ids = +(id >> ',');
            'symdef_ids' => [
                PlusOperator::class, [
                    [SequenceOperator::class, [
                        'id',
                        [LitParser::class, [',']],
                    ]],
                ]
            ],

            // symboltable_definition = "qi::symbols" >> lit('<') >> "char" >> lit(',') >> symbol_type
            // >> symdef_ids >> lit(';');
            'symboltable_definition' => [
                SequenceOperator::class, [
                    [LitParser::class, ['qi::symbols']],
                    [LitParser::class, ['<']],
                    [LitParser::class, ['char']],
                    [LitParser::class, [',']],
                    'symbol_type',
                    'symdef_ids',
                    [LitParser::class, [';']],
                ],
            ],

            ///////////////////////////////////////////////////////////////////////
            // Expression grammar

            // precedence level 14: logical or
            // prec_14_expr = prec_12_expr >> *(prec_14_op > prec_12_expr);
            'prec_14_expr' => [
                SequenceOperator::class, [
                    'prec_12_expr',
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            'prec_14_op',
                            'prec_12_expr',
                        ]],
                    ]],
                ],
            ],
            // precedence level 12: bitwise or
            // prec_12_expr = prec_11_expr >> *(prec_12_op > prec_11_expr);
            'prec_12_expr' => [
                SequenceOperator::class, [
                    'prec_11_expr',
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            'prec_12_op',
                            'prec_11_expr',
                        ]],
                    ]],
                ],
            ],
            // precedence level 11: bitwise xor
            // prec_11_expr = prec_8_expr >> *(prec_11_op > prec_8_expr);
            'prec_11_expr' => [
                SequenceOperator::class, [
                    'prec_8_expr',
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            'prec_11_op',
                            'prec_8_expr',
                        ]],
                    ]],
                ],
            ],
            // precedence level 8: comparison operators
            // prec_8_expr = prec_7_expr >> *(prec_8_op > prec_7_expr);
            'prec_8_expr' => [
                SequenceOperator::class, [
                    'prec_7_expr',
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            'prec_8_op',
                            'prec_7_expr',
                        ]],
                    ]],
                ],
            ],

            // precedence level 7: bitwise shift operators
            // prec_7_expr = prec_6_expr >> *(prec_7_op > prec_6_expr);
            'prec_7_expr' => [
                SequenceOperator::class, [
                    'prec_6_expr',
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            'prec_7_op',
                            'prec_6_expr',
                        ]],
                    ]],
                ],
            ],
            // precedence level 6: additive operators
            // prec_6_expr = prec_5_expr >> *(prec_6_op > prec_5_expr);
            'prec_6_expr' => [
                SequenceOperator::class, [
                    'prec_5_expr',
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            'prec_6_op',
                            'prec_5_expr',
                        ]],
                    ]],
                ],
            ],
            // precedence level 5: multiplicative operators
            // prec_5_expr = prec_3_expr >> *(prec_5_op > prec_3_expr);
            'prec_5_expr' => [
                SequenceOperator::class, [
                    'prec_3_expr',
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            'prec_5_op',
                            'prec_3_expr',
                        ]],
                    ]],
                ],
            ],
            // precedence level 3: unary operators
            // look at precedence 2 first
            // note: this does not work without distinct
            // unary_expr rule
            //
            // prec_3_expr = prec_2_expr | unary_expr;
            'prec_3_expr' => [
                AlternativeOperator::class, [
                    'prec_2_expr',
                    'unary_expr',
                ],
            ],

            // unary_expr = prec_3_op > prec_2_expr;
            'unary_expr' => [
                ExpectOperator::class, [
                    'prec_3_op',
                    'prec_2_expr',
                ],
            ],
            // precedence level 2: function call, array []
            // prec_2_expr =
            //      parser
            //      | directive
            //      | distinct_expr
            //      | id
            //      | quoted_string
            //      | quoted_char
            //      | paren_expr
            'prec_2_expr' => [
                AlternativeOperator::class, [
                    'parsers',
                    'directives',
                    'distinct_expr',
                    'id',
                    'quoted_string',
                    'quoted_char',
                    'paren_expr'

                ]
            ],
            // identifier = *omit[(alpha | '_') >> *(alnum | '_') >> "::"]
            //   >> char_("a-z_") >> *char_("a-z_0-9");
            'identifier' => [
                SequenceOperator::class, [
                    [KleeneOperator::class, [
                        [OmitDirective::class, [
                            [SequenceOperator::class, [
                                [AlternativeOperator::class, [
                                    [AlphaParser::class, []],
                                    [LitParser::class, ['_']],
                                ]],
                                [KleeneOperator::class, [
                                    [AlternativeOperator::class, [
                                        [AlnumParser::class, []],
                                        [LitParser::class, ['_']],
                                    ]],
                                ]],
                                [LitParser::class, ['::']],
                            ]]
                        ]]
                    ]],
                    [CharSetParser::class, ['a-z_']],
                    [KleeneOperator::class, [
                        [CharSetParser::class, ['a-z_0-9']],
                    ]],
                ],
            ],

            // id = identifier >> attr(token_type::op_identifier);
            'id' => [
                SequenceOperator::class, [
                    'identifier',
                    [AttrParser::class, ['identifier']],
                ],
            ],

            // paren_expr = lit('(') > attr(token_type::op_brace_open) > prec_14_expr > lit(')');
            'paren_expr' => [
                ExpectOperator::class, [
                    [CharParser::class, ['(']],
                    'prec_14_expr',
                    [LitParser::class, [')']],
                ]
            ],

            'parser' => [
                ExpectOperator::class => [
                    [DistinctDirective::class, [
                        [CharSetParser::class, ['a-z_']],
                        'parsers',
                    ]],
                    'argument_list',
                ],
            ],
            // distinct_expr = distinct(char_("a-z_"))[distinct_directive]
            //      > '(' > prec_14_expr > ')' > '[' > prec_14_expr > ']'
            'distinct_expr' => [
                ExpectOperator::class, [
                    [DistinctDirective, [
                        [CharSetParser::class, ['a-z_']],
                        'distinct_directive',
                    ]],
                    [LitParser::class, ['(']],
                    'prec_14_expr',
                    [LitParser::class, [')']],
                    [LitParser::class, ['[']],
                    'prec_14_expr',
                    [LitParser::class, [']']],
                ]
            ],
            // directive = distinct(char_("a-z_"))[directives] > argument_list > '[' > prec_14_expr > ']'
            'directive' => [
                ExpectOperator::class, [
                    [DistinctDirective, ['a-z_', 'directives']],
                    'argument_list',
                    [LitParser::class, ['[']],
                    'prec_14_expr',
                    [LitParser::class, [']']],
                ],
            ],
            // argument_list = -('(' > (quoted_char | quoted_string | id | number) % lit(',') > ')')
            'argument_list' => [
                OptionalOperator::class, [
                    [ExpectOperator::class, [
                        [LitParser::class, ['(']],
                        [ListOperator::class, [
                            [AlternativeOperator::class, [
                                'quoted_char',
                                'quoted_string',
                                'id',
                                'number',
                            ]],
                            [LitParser::class, [',']],
                        ]],
                        [LitParser::class, [')']],
                    ]],
                ]
            ],
            // quoted_string = lexeme[char_('"') >> *(char_ - '"') >> char_('"')] >> attr(token_type::quoted_string)
            'quoted_string' => [
                SequenceOperator::class, [
                    [LexemeDirective::class, [
                        [SequenceOperator::class, [
                            [CharParser::class, ['"']],
                            [KleeneOperator::class, [
                                [DifferenceOperator::class, [
                                    [CharParser::class, []],
                                    [LitParser::class, ['"']],
                                ]],
                                [CharParser::class, ['"']],
                            ]],
                            [CharParser::class, ['"']],
                        ]]
                    ]],
                    [AttrParser::class, ['quoted_string']],
                ],
            ],

            // quoted_char = lexeme[string("'") >> char_ >> char_("'")] >> attr(token_type::quoted_char)
            'quoted_char' => [
                SequenceOperator::class, [
                    [LexemeDirective::class, [
                        [SequenceOperator::class, [
                            [CharParser::class, ["'"]],
                            [CharParser::class, []],
                            [CharParser::class, ["'"]]
                        ]]
                    ]],
                    [AttrParser::class, ['quoted_char']],
                ],
            ],
            'ulong_long' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [ULongLongParser::class, []],
                    ]],
                    [AttrParser::class, ['ulonglong']],
                ],
            ],
            'ulong' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [ULongParser::class, []],
                    ]],
                    [AttrParser::class, ['ulong']],
                ],
            ],
            'uint' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [UIntParser::class, []],
                    ]],
                    [AttrParser::class, ['uint']],
                ],
            ],
            'ushort' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [UShortParser::class, []],
                    ]],
                    [AttrParser::class, ['ushort']],
                ],
            ],
            'longlong' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [LongLongParser::class, []],
                    ]],
                    [AttrParser::class, ['longlong']],
                ],
            ],
            'long' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [LongParser::class, []],
                    ]],
                    [AttrParser::class, ['long']],
                ],
            ],
            'int' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [IntParser::class, []],
                    ]],
                    [AttrParser::class, ['int']],
                ],
            ],
            'short' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [ShortParser::class, []],
                    ]],
                    [AttrParser::class, ['short']],
                ],
            ],
            'bin' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [BinaryParser::class, []],
                    ]],
                    [AttrParser::class, ['bin']],
                ],
            ],
            'oct' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [OctParser::class, []],
                    ]],
                    [AttrParser::class, ['oct']],
                ],
            ],
            'hex' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [HexParser::class, []],
                    ]],
                    [AttrParser::class, ['hex']],
                ],
            ],
            'bool' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [BoolParser::class, []],
                    ]],
                    [AttrParser::class, ['bool']],
                ],
            ],
            'float' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [FloatParser::class, []],
                    ]],
                    [AttrParser::class, ['float']],
                ],
            ],
            'double' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [DoubleParser::class, []],
                    ]],
                    [AttrParser::class, ['double']],
                ],
            ],
            'long_double' => [
                SequenceOperator::class, [
                    [RawDirective::class, [
                        [LongDoubleParser::class, []],
                    ]],
                    [AttrParser::class, ['long_double']],
                ],
            ],
            // number =  ushort_p | uint_p | ulong_p | ulong_long_p | short_p | int_p | long_p | long_long_p
            // | float_p | double_p | long_double_p | bool_p
            'number' => [
                AlternativeOperator::class, [
                    'ushort',
                    'uint',
                    'ulong',
                    'ulonglong',
                    'short',
                    'int',
                    'long',
                    'longlong',
                    'float',
                    'double',
                    'longdouble',
                    'bool',
                ]],
            ],
        ];
    }

    public function createParser(string $class, array $args)
    {
        return $this->pm->build($class, $args);
    }

    public function setRuleParser(string $name, Parser $parser)
    {
        $rule = $this->rule[$name] ? $this->rule[$name] : null;
        if ($rule === null) {
            $rule = $this->createParser(
                Rule::class,
                [$name]
            );
            $this->rules[$name] = $rule;
        }
        $rule->setParser($parser);
    }
}
