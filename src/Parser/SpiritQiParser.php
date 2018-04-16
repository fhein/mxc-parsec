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

class Symbols {}

class SpiritQiParser
{
    protected $pm;
    public $rules;

    public function __construct(ParserManager $pm, string $source)
    {
        $this->pm = $pm;

        $hex_char = "0-9a-zA-Z";
        $msg = "Expected ";

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

        $escapedChars = $this->pm->build(SymbolsParser::class);
        $escapedChars
        ->add(escaped_u, escaped_u)
        ->add(escaped_x, escaped_x)
        ->add(escaped_single_quote, escaped_single_quote)
        ->add(escaped_quote, escaped_quote)
        ->add(escaped_question_mark, escaped_question_mark)
        ->add(escaped_backslash, escaped_backslash)
        ->add(escaped_a, escaped_a)
        ->add(escaped_b, escaped_b)
        ->add(escaped_f, escaped_f)
        ->add(escaped_n, escaped_n)
        ->add(escaped_r, escaped_r)
        ->add(escaped_t, escaped_t)
        ->add(escaped_v, escaped_v)
        ;

        $directives = $this->pm->build(SymbolsParser::class);
        $directives
        ->add("lexeme", LexemeDirective::class)
        ->add("no_skip", NoSkipDirective::class)
        ->add("no_case", NoCaseDirective::class)
        ->add("omit", OmitDirective::class)
        ->add("matches", MatchesDirective::class)
        ->add("as_string", AsStringDirective::class)
        ->add("raw", RawDirective::class)
        ->add("skip", SkipDirective::class)
        ->add("hold", HoldDirective::class)
        ->add("repeat", RepeatDirective::class)
        //   ->add("as", AsDirective::class)
        //   ->add("as_wstring", as_wstring_directive)
        // directives from repository
        //  ->add("confix", token_type::confix_directive)
        //  ->add("kwd", token_type::keyword_directive)
        //  ->add("ikwd", token_type::ignore_case_keyword_directive)
        //  ->add("seek", token_type::seek_directive)
        ;

        // qi parsers should not take parsers as
        // arguments to their constructors
        // distinct does not comply to this
        // convention, so we need special handling
        $distinctDirective = $this->pm->build(SymbolsParser::class);
        $distinctDirective
        ->add("distinct", DistinctDirective::class);

        // unary operators
        $prec_3_op = $this->pm->build(SymbolsParser::class);
        $prec_3_op->setName("unary operator");
        $prec_3_op
        ->add("-", OptionalOperator::class)
        ->add("!", NotPredicate::class)
        ->add("+", PlusOperator::class)
        ->add("*", KleeneOperator::class)
        ->add("&", AndPredicate::class)
        ;

        $prec_5_op = $this->pm->build(SymbolsParser::class);
        $prec_5_op->setName("% or /");
        $prec_5_op
        ->add("%", ListOperator::class)
//      ->add("/", KeywordListOperator::class)  // repository
        ;

        $prec_6_op = $this->pm->build(SymbolsParser::class);
        $prec_6_op->setName("difference operator");
        $prec_6_op
        ->add("-", DifferenceOperator::class)
        ;

        $prec_7_op = $this->pm->build(SymbolsParser::class);
        $prec_7_op->setName("sequence operator");
        $prec_7_op
        ->add(">>", SequenceOperator::class)
        ;

        $prec_8_op = $this->pm->build(SymbolsParser::class);
        $prec_8_op->setName('expect operator');
        $prec_8_op
        ->add(">", ExpectOperator::class)
        ;

        $prec_11_op = $this->pm->build(SymbolsParser::class);
        $prec_11_op->setName("permutation operator");
        $prec_11_op
        ->add("^", PermutationOperator::class)
        ;

        $prec_12_op = $this->pm->build(SymbolsParser::class);
        $prec_12_op->setName("alternative operator");
        $prec_12_op
        ->add("|", AlternativeOperator::class)
        ;

        $prec_14_op = $this->pm->build(SymbolsParser::class);
        $prec_14_op->setName("sequential-or operator");
        $prec_14_op
        ->add("||", SequentialOrOperator::class)
        ;

        $prec_15_op = $this->pm->build(SymbolsParser::class);
        $prec_15_op->setName("assignment operator");
        $prec_15_op
        ("=", Rule::class)
        ("%=", Rule::class)
        ;

        $parsers = $this->pm->build(SymbolsParser::class);
        $parsers->setName("parser");
        $parsers
        ->add("float_", FloatParser::class)
        ->add("double_", DoubleParser::class)
        ->add("long_double", LongDoubleParser::class)
        ->add("bin", BinaryParser::class)
        ->add("oct", OctParser::class)
        ->add("hex", HexParser::class)
        ->add("ushort_", UShortParser::class)
        ->add("ulong_", ULongParser::class)
        ->add("uint_", UIntParser::class)
        ->add("ulong_long", ULongLongParser::class)
        ->add("short_", ShortParser::class)
        ->add("long_", LongParser::class)
        ->add("int_", IntParser::class)
        ->add("long_long", LongLongParser::class)
        ->add("eps", EpsParser::class)
        ->add("lazy", LazyParser::class)
        ->add("attr", AttrParser::class)
        ->add("byte_", ByteParser::class)
        ->add("word", WordParser::class)
        ->add("big_word", BigWordParser::class)
        ->add("little_word", LittleWordParser::class)
        ->add("qword", QWordParser::class)
        ->add("big_qword", BigQWordParser::class)
        ->add("little_qword", LittleQWordParser::class)
        ->add("bin_float", BinFloatParser::class)
        ->add("big_bin_float", BigBinFloatParser::class)
        ->add("little_bin_float", LittleBinFloatParser::class)
        ->add("bin_double", BinDoubleParser::class)
        ->add("big_bin_double", BigBinDoubleParser::class)
        ->add("little_bin_double", LittleBinDoubleParser::class)
        ->add("char_", CharParser::class)
        ->add("string", StringParser::class)
        ->add("lit", LitParser::class)
        ->add("advance", AdvanceParser::class)
        ->add("eol", EolParser::class)
        ->add("eoi", EoiParser::class)
//        ->add("auto_", AutoParser::class) @todo: Necessary?
        ->add("alnum", AlnumParser::class)
        ->add("alpha", AlphaParser::class)
        ->add("blank", BlankParser::class)
        ->add("cntrl", CntrlParser::class)
        ->add("digit", DigitParser::class)
        ->add("graph", GraphParser::class)
        ->add("print", PrintParser::class)
        ->add("punct", PunctParser::class)
        ->add("space", SpaceParser::class)
        ->add("xdigit", XDigitParser::class)
        ->add("lower", LowerParser::class)
        ->add("upper", UpperParser::class)
        ->add("bool_", BoolParser::class)
        ->add("true_", TrueParser::class)
        ->add("false_", FalseParser::class)
        ;


        $this->rules = [
            // start = +(symboltable | assignment | symboltable_definition);
            'start' => [
                PlusOperator::class, [
                    [AlternativeOperator::class, [
                        [Rule::class, ['symboltable']],
                        [Rule::class, ['assignment']],
                        [Rule::class, ['symboltable_definition']],
                    ]],
                ],
            ],
            // precedence level 15 (lowest): assignment
            // assignment = id >> rule_operation > lit(';');
            'assignment' => [[
                ExpectOperator::class, [
                    [SequenceOperator::class, [
                        [Rule::class, ['id']],
                        [Rule::class, ['rule_operation']]
                    ]],
                ]
            ],
            // rule_operation = prec_15_op > prec_14_expr;
            'rule_operation' => [
                ExpectOperator::class, [
                    [SymbolsParser::class, ['prec_15_op']],
                    [Rule::class, ['prec_14_expr']]
                ]
            ],

            // symboltable = identifier >> lit('.') >> lit('add') >> +symbol >> lit(';')
            'symbol_table' => [
                SequenceOperator::class, [
                    [LitParser::class, ['.add']],
                    [PlusOperator::class, [
                        [Rule::class, ['symbol']],
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
                [Rule::class, ['identifier']]
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
                        [Rule::class, ['name']],
                        [LitParser::class, [',']],
                        [Rule::class, ['value']],
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
                        [Rule::class, ['id']],
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
                    [Rule::class, ['symbol_type']],
                    [Rule::class, ['symdef_ids']],
                    [LitParser::class, [';']],
                ],
            ],

            ///////////////////////////////////////////////////////////////////////
            // Expression grammar

            // precedence level 14: logical or
            // prec_14_expr = prec_12_expr >> *(prec_14_op > prec_12_expr);
            'prec_14_expr' => [
                SequenceOperator::class, [
                    [Rule::class,  ['prec_12_expr']],
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            [Rule::class, ['prec_14_op']],
                            [Rule::class, ['prec_12_expr']],
                        ]],
                    ]],
                ],
            ],
            // precedence level 12: bitwise or
            // prec_12_expr = prec_11_expr >> *(prec_12_op > prec_11_expr);
            'prec_12_expr' => [
                SequenceOperator::class, [
                    [Rule::class,  ['prec_11_expr']],
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            [Rule::class, ['prec_12_op']],
                            [Rule::class, ['prec_11_expr']],
                        ]],
                    ]],
                ],
            ],
            // precedence level 11: bitwise xor
            // prec_11_expr = prec_8_expr >> *(prec_11_op > prec_8_expr);
            'prec_11_expr' => [
                SequenceOperator::class, [
                    [Rule::class,  ['prec_8_expr']],
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            [Rule::class, ['prec_11_op']],
                            [Rule::class, ['prec_8_expr']],
                        ]],
                    ]],
                ],
            ],
            // precedence level 8: comparison operators
            // prec_8_expr = prec_7_expr >> *(prec_8_op > prec_7_expr);
            'prec_8_expr' => [
                SequenceOperator::class, [
                    [Rule::class,  ['prec_7_expr']],
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            [Rule::class, ['prec_8_op']],
                            [Rule::class, ['prec_7_expr']],
                        ]],
                    ]],
                ],
            ],

            // precedence level 7: bitwise shift operators
            // prec_7_expr = prec_6_expr >> *(prec_7_op > prec_6_expr);
            'prec_7_expr' => [
                SequenceOperator::class, [
                    [Rule::class,  ['prec_6_expr']],
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            [Rule::class, ['prec_7_op']],
                            [Rule::class, ['prec_6_expr']],
                        ]],
                    ]],
                ],
            ],
            // precedence level 6: additive operators
            // prec_6_expr = prec_5_expr >> *(prec_6_op > prec_5_expr);
            'prec_6_expr' => [
                SequenceOperator::class, [
                    [Rule::class,  ['prec_5_expr']],
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            [Rule::class, ['prec_6_op']],
                            [Rule::class, ['prec_5_expr']],
                        ]],
                    ]],
                ],
            ],
            // precedence level 5: multiplicative operators
            // prec_5_expr = prec_3_expr >> *(prec_5_op > prec_3_expr);
            'prec_5_expr' => [
                SequenceOperator::class, [
                    [Rule::class,  ['prec_3_expr']],
                    [KleeneOperator::class, [
                        [ExpectOperator::class, [
                            [Rule::class, ['prec_5_op']],
                            [Rule::class, ['prec_3_expr']],
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
                    [Rule::class,  ['prec_2_expr']],
                    [Rule::class,  ['unary_expr']],
                ],
            ],

            // unary_expr = prec_3_op > prec_2_expr;
            'unary_expr' => [
                ExpectOperator::class, [
                    [Rule::class,  ['prec_3_op']],  // @todo symbols
                    [Rule::class,  ['prec_2_expr']],
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
                    [Symbols::class, ['parsers']],
                    [Symbols::class, ['directives']],
                    $distinct_expr,
                    [Rule::class, ['id']],
                    [Rule::class, ['quoted_string']],
                    [Rule::class, ['quoted_char']],
                    [Rule::class, ['paren_expr']]

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
                    [KleeneOperator, [
                        [CharSetParser, ['a-z_0-9']],
                    ]],
                ],
            ],

            // id = identifier >> attr(token_type::op_identifier);
            'id' => [
                SequenceOperator::class, [
                    [Rule::class, ['identifier']],
                    [AttrParser::class, ['identifier']],
                ]
            ],

            // paren_expr = lit('(') > attr(token_type::op_brace_open) > prec_14_expr > lit(')');
            'paren_expr' => [
                ExpectOperator::class, [
                    [CharParser::class, ['(']],
                    [Rule::class, ['prec_14_expr']],
                    [LitParser::class, [')']],
                ]
            ],

            'parser' => [
                ExpectOperator::class => [
                    [DistinctDirective::class, [
                        [CharSetParser::class, ['a-z_']],
                        [Symbols::class, ['parsers']],
                    ]],
                    [Rule::class, ['argument_list']],
                ],
            ],
            // distinct_expr = distinct(char_("a-z_"))[distinct_directive]
            //      > '(' > prec_14_expr > ')' > '[' > prec_14_expr > ']'
            'distinct_expr' => [
                ExpectOperator::class, [
                    [DistinctDirective, [
                        [CharSetParser::class, ['a-z_']],
                        [Symbols::class, ['distinct_directive']],
                    ]],
                    [LitParser::class, ['(']],
                    [Rule::class, ['prec_14_expr']],
                    [LitParser::class, [')']],
                    [LitParser::class, ['[']],
                    [Rule::class, ['prec_14_expr']],
                    [LitParser::class, [']']],
                ]
            ],
            // directive = distinct(char_("a-z_"))[directives] > argument_list > '[' > prec_14_expr > ']'
            'directive' => [
                ExpectOperator::class, [
                    [DistinctDirective, ['a-z_', [Symbols::class, ['directives']]]],
                    [Rule::class, ['argument_list']],
                    [LitParser::class, ['[']],
                    [Rule::class, ['prec_14_expr']],
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
                                [Rule::class, ['quoted_char']],
                                [Rule::class, ['quoted_string']],
                                [Rule::class, ['id']],
                                [Rule::class, ['number']],
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
                    [Rule::class, ['ushort']],
                    [Rule::class, ['uint']],
                    [Rule::class, ['ulong']],
                    [Rule::class, ['ulonglong']],
                    [Rule::class, ['short']],
                    [Rule::class, ['int']],
                    [Rule::class, ['long']],
                    [Rule::class, ['longlong']],
                    [Rule::class, ['float']],
                    [Rule::class, ['double']],
                    [Rule::class, ['longdouble']],
                    [Rule::class, ['bool']],
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
