<?php

namespace Mxc\Parsec\Parser;

use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Service\ParserBuilder;
use Mxc\Parsec\Qi\Domain;

class SpiritQiParser
{
    // These are not aliases but qi token
    //     protected $aliases =
    //     [
        //         // auxiliary
        //         'eol'               => EolParser::class,
        //         'attr'              => AttrParser::class,
        //         'eoi'               => EoiParser::class,
        //         'eps'               => EpsParser::class,
        //         'lazy'              => LazyParser::class,
        //         'lit'               => LitParser::class,
        //         // binary
        //         'byte'              => ByteParser::class,
        //         'word'              => WordParser::class,
        //         'dword'             => DWordParser::class,
        //         'qword'             => QWordParser::class,
        //         'big_word'          => BigWordParser::class,
        //         'big_dword'         => BigDWordParser::class,
        //         'big_qword'         => BigQWordParser::class,
        //         'little_word'       => LittleWordParser::class,
        //         'little_dword'      => LittleDWordParser::class,
        //         'little_qword'      => LittleQWordParser::class,
        //         'bin_double'        => BinDoubleParser::class,
        //         'bin_float'         => BinFloatParser::class,
        //         'big_bin_double'    => BigBinDoubleParser::class,
        //         'big_bin_float'     => BigBinFloatParser::class,
        //         'little_bin_double' => LittleBinDoubleParser::class,
        //         'little_bin_float'  => LittleBinFloatParser::class,

        //         // char
        //         'char_class'        => CharClassParser::class,
        //         'char'              => CharParser::class,
        //         'char_range'        => CharRangeParser::class,
        //         'char_set'          => CharSetParser::class,
        //         '~char_class'       => '~' . CharClassParser::class,
        //         '~char'             => '~' . CharParser::class,
        //         '~char_range'       => '~' . CharRangeParser::class,
        //         '~char_set'         => '~' . CharSetParser::class,
        //         // directive
        //         'expect'            => ExpectDirective::class,
        //         'hold'              => HoldDirective::class,
        //         'lexeme'            => LexemeDirective::class,
        //         'matches'           => MatchesDirective::class,
        //         'no_case'           => NoCaseDirective::class,
        //         'no_skip'           => NoSkipDirective::class,
        //         'omit'              => OmitDirective::class,
        //         'raw'               => RawDirective::class,
        //         'repeat'            => RepeatDirective::class,
        //         'skip'              => SkipDirective::class,
        //         'as_string'         => AsStringDirective::class,
        //         // nonterminal
        //         'rule'              => Rule::class,
        //         'grammar'           => Grammar::class,
        //         // numeric
        //         'binary'            => BinaryParser::class,
        //         'bool'              => BoolParser::class,
        //         'true_'             => TrueParser::class,
        //         'false_'            => FalseParser::class,
        //         'hex'               => HexParser::class,
        //         'oct'               => OctParser::class,
        //         'short_'            => ShortParser::class,
        //         'int_'              => IntParser::class,
        //         'long_'             => LongParser::class,
        //         'long_long'         => LongLongParser::class,
        //         'ushort_'           => UShortParser::class,
        //         'uint_'             => UIntParser::class,
        //         'ulong_'            => ULongParser::class,
        //         'ulong_long'        => ULongLongParser::class,
        //         'float_'            => FloatParser::class,
        //         'double_'           => DoubleParser::class,
        //         'long_double'       => DoubleParser::class,
        //         //operator
        //         '|'                 => AlternativeOperator::class,
        //         '&'                 => AndPredicate::class,
        //         '-'                 => DifferenceOperator::class,
        //         '>'                 => ExpectOperator::class,
        //         '*'                 => KleeneOperator::class,
        //         '%'                 => ListOperator::class,
        //         '!'                 => NotPredicate::class,
        //         'minus'             => OptionalOperator::class,
        //         '^'                 => PermutationOperator::class,
        //         '+'                 => PlusOperator::class,
        //         '>>'                => SequenceOperator::class,
        //         '||'                => SequentialOrOperator::class,
        //         // string
        //         'string'            => StringParser::class,
        //         'symbols'           => SymbolsParser::class,

        //         // Repository
        //         // directives
        //         'distinct'          => DistinctDirective::class,
        //         // auxiliary
        //         'advance'           => AdvanceParser::class,
        //     ];


    protected $pm;
    protected $rules = [
        'directives' => ['rule', [
            'directives',
            ['symbols', [[
                "lexeme" => 'lexeme',
                "no_skip" => 'no_skip',
                "no_case" => 'no_case',
                "omit" => 'omit',
                "matches" => 'matches',
                "as_string" => 'as_string',
                "raw" => 'raw',
                "skip" => 'skip',
                "hold" => 'hold',
                "repeat" => 'repeat',
                //   "as" => AsDirective::class,
                //   "as_wstring" => as_wstring_directive,
                // directives from repository
                //  "confix" => token_type::confix_directive,
                //  "kwd" => token_type::keyword_directive,
                //  "ikwd" => token_type::ignore_case_keyword_directive,
                //  "seek" => token_type::seek_directive,
            ]]],
        ]],

        'distinct_directive' => ['rule', [
            'distinct_directive',
            ['symbols', [[
                'distinct' => 'distinct',
            ]]],
        ]],

        'prec_3_op' => ['rule', [
            'prec_3_op',
            ['symbols', [[
                "-" => 'optional',
                "!" => 'not',
                "+" => 'plus',
                "*" => 'kleene',
                "&" => 'and',
            ]]],
        ]],

        'prec_5_op' => ['rule', [
            'prec_5_op',
            ['symbols', [[
                '%' => 'list',
            ]]],
        ]],

        'prec_6_op' => ['rule', [
            'prec_6_op',
            ['symbols', [[
                '-' => 'difference',
            ]]],
        ]],

        'prec_7_op' => ['rule', [
            'prec_7_op',
            ['symbols', [[
                '>>' => 'sequence',
            ]]],
        ]],

        'prec_8_op' => ['rule', [
            'prec_8_op',
            ['symbols', [[
                '>' => 'expect',
            ]]],
        ]],

        'prec_11_op' => ['rule', [
            'prec_11_op',
            ['symbols', [[
                '^' => 'permutation',
            ]]],
        ]],

        'prec_12_op' => ['rule', [
            'prec_12_op',
            ['symbols', [[
                '|' => 'alternative',
            ]]],
        ]],

        'prec_14_op' => ['rule', [
            'prec_14_op',
            ['symbols', [[
                '||' => 'sequential_or',
            ]]],
        ]],

        'prec_15_op' => ['rule', [
            'prec_15_op',
            ['symbols', [[
                '=' => 'rule',
                '%=' => 'rule',
            ]]],
        ]],

        'escaped_chars' => ['rule', [
            'escaped_chars',
            ['symbols', [[
                'backslash' => "\\",
                'escaped_u' => "\\U",
                'escaped_x' => "\\X",
                'escaped_single_quote' => "\\\'",
                'escaped_quote' => "\\\"",
                'escaped_question_mark' => "\\\?",
                'escaped_backslash' => "\\\\",
                'escaped_a' => "\\\a",
                'escaped_b' => "\\\b",
                'escaped_f' => "\\\f",
                'escaped_n' => "\\\n",
                'escaped_r' => "\\\r",
                'escaped_t' => "\\\t",
                'escaped_v' => "\\\v",
            ]]],
        ]],

        'parsers' => ['rule', [
            'parsers',
            ['symbols', [[
                "float_" => 'float',
                "double_" => 'double',
                "long_double" => 'long_double',
                "bin" => 'bin',
                "oct" => 'oct',
                "hex" => 'hex',
                "ushort_" => 'ushort',
                "ulong_" => 'ulong',
                "uint_" => 'uint',
                "ulong_long" => 'ulong_long',
                "short_" => 'short',
                "long_" => 'long',
                "int_" => 'int',
                "long_long" => 'long_long',
                "eps" => 'eps',
                "lazy" => 'lazy',
                "attr" => 'attr',
                "byte_" => 'byte',
                "word" => 'word',
                "big_word" => 'big_word',
                "little_word" => 'little_word',
                "qword" => 'qword',
                "big_qword" => 'big_qword',
                "little_qword" => 'little_qword',
                "bin_float" => 'bin_float',
                "big_bin_float" => 'big_bin_float',
                "little_bin_float" => 'little_bin_float',
                "bin_double" => 'bin_double',
                "big_bin_double" => 'big_bin_double',
                "little_bin_double" => 'little_bin_double',
                "char_" => 'char',
                "string" => 'string',
                "lit" => 'lit',
                "advance" => 'advance',
                "eol" => 'eol',
                "eoi" => 'eoi',
                //        "auto_" => AutoParser::class, @todo: Necessary?
                "alnum" => 'alnum',
                "alpha" => 'alpha',
                "blank" => 'blank',
                "cntrl" => 'cntrl',
                "digit" => 'digit',
                "graph" => 'graph',
                "print" => 'print',
                "punct" => 'punct',
                "space" => 'space',
                "xdigit" => 'xdigit',
                "lower" => 'lower',
                "upper" => 'upper',
                "bool_" => 'bool',
                "true_" => 'true',
                "false_" => 'false',
            ]]],
        ]],
        // start = +(symboltable | assignment | symboltable_definition);
        'start' => ['rule', [
            'start',
            ['plus', [
                ['alternative', [[
                    ['ref', [ 'symboltable' ]],
                    ['ref', [ 'assignment' ]],
                    ['ref', [ 'symboltable_definition' ]],
                ]]],
            ]],
        ]],

        // precedence level 15 (lowest): assignment
        // assignment = id >> rule_operation > lit(';');
        'assignment' => ['rule', [
            'assignment',
            ['expect', [[
                [ 'sequence', [[
                    ['ref', [ 'id' ]],
                    ['ref', [ 'rule_operation' ]],
                ]]],
                [ 'lit', [';']],
            ]]],
        ]],

        // rule_operation = prec_15_op > prec_14_expr;
        'rule_operation' => ['rule', [
            'rule_operation',
            ['expect', [[
                ['ref', [ 'prec_15_op' ]],
                ['ref', [ 'prec_14_expr' ]],
            ]]],
        ]],

        // symboltable = identifier >> lit('.') >> lit('add') >> +symbol >> lit(';')
        'symbol_table' => ['rule', [
            'symbol_table',
            ['sequence', [[
                ['lit', ['.add']],
                ['plus', [
                    ['ref', [ 'symbol' ]],
                ]],
                ['lit', [';']],
            ]]],
        ]],

        // name = (lit('"') >> *(char_ - '"') >> '"') | identifier;
        'name' => ['rule', [
            'name',
            ['alternative', [[
                ['sequence', [[
                    ['lit', ['"']],
                    ['kleene', [
                        ['difference', [
                            ['char', []],
                            ['lit', ['"']],
                        ]],
                    ]],
                    ['lit', ['"']],
                ]]],
                ['ref', [ 'identifier', ]],
            ]]],
        ]],

        // value = *(char_ - ')')
        'value' => ['rule', [
            'value',
            ['kleene', [
                ['difference', [
                    ['char', []],
                    ['lit',  [')']],
                ]],
            ]],
        ]],

        // symbol = '(' >> name >> lit(',') >> value > ')';
        'symbol' => ['rule', [
            'symbol',
            ['expect', [[
                ['sequence', [[
                    ['lit', ['(']],
                    ['ref', ['name']],
                    ['lit', [',']],
                    ['ref', ['value']],
                ]]],
                ['lit', [')']],
            ]]],
        ]],

        // symbol_type = *(char_ - '>') >> attr(token_type::op_symboltype);
        'symbol_type' => ['rule', [
            'symbol_type',
            ['sequence', [[
                ['kleene', [
                    ['difference', [
                        ['char', []],
                        ['lit', ['>']],
                    ]],
                ]],
                ['attr', ['symboltype']],
            ]]],
        ]],

        // symdef_ids = +(id >> ',');
        'symdef_ids' => ['rule', [
            'symdef_ids',
            ['plus', [
                ['sequence', [[
                    ['ref', ['id']],
                    ['lit', [',']],
                ]]],
            ]],
        ]],

        // symboltable_definition = "qi::symbols" >> lit('<') >> "char" >> lit(',') >> symbol_type
        // >> symdef_ids >> lit(';');
        'symboltable_definition' => ['rule', [
            'symboltable_definition',
            ['sequence', [[
                ['lit', ['qi::symbols']],
                ['lit', ['<']],
                ['lit', ['char']],
                ['lit', [',']],
                ['ref', ['symbol_type']],
                ['ref', ['symdef_ids']],
                ['lit', [';']],
            ]]],
        ]],

        ///////////////////////////////////////////////////////////////////////
        // Expression grammar

        // precedence level 14: logical or
        // prec_14_expr = prec_12_expr >> *(prec_14_op > prec_12_expr);
        'prec_14_expr' => ['rule', [
            'prec_14_expr',
            ['sequence', [[
                ['ref', ['prec_12_expr']],
                ['kleene', [
                    ['expect', [[
                        ['ref', ['prec_14_op']],
                        ['ref', ['prec_12_expr']],
                    ]]],
                ]],
            ]]],
        ]],

        // precedence level 12: bitwise or
        // prec_12_expr = prec_11_expr >> *(prec_12_op > prec_11_expr);
        'prec_12_expr' => ['rule', [
            'prec_12_expr',
            ['sequence', [[
                ['ref', ['prec_11_expr']],
                ['kleene', [
                    ['expect', [[
                        ['ref', ['prec_12_op']],
                        ['ref', ['prec_11_expr']],
                    ]]],
                ]],
            ]]],
        ]],

        // precedence level 11: bitwise xor
        // prec_11_expr = prec_8_expr >> *(prec_11_op > prec_8_expr);
        'prec_11_expr' => ['rule', [
            'prec_11_expr',
            ['sequence', [[
                ['ref', ['prec_8_expr']],
                ['kleene', [
                    ['expect', [[
                        ['ref', ['prec_11_op']],
                        ['ref', ['prec_8_expr']],
                    ]]],
                ]],
            ]]],
        ]],

        // precedence level 8: comparison operators
        // prec_8_expr = prec_7_expr >> *(prec_8_op > prec_7_expr);
        'prec_8_expr' => ['rule', [
            'prec_8_expr',
            ['sequence', [[
                ['ref', [ 'prec_7_expr' ]],
                ['kleene', [
                    ['expect', [[
                        ['ref', [ 'prec_8_op' ]],
                        ['ref', [ 'prec_7_expr' ]],
                    ]]],
                ]],
            ]]],
        ]],

        // precedence level 7: bitwise shift operators
        // prec_7_expr = prec_6_expr >> *(prec_7_op > prec_6_expr);
        'prec_7_expr' => ['rule', [
            'prec_7_expr',
            ['sequence', [[
                ['ref', [ 'prec_6_expr' ]],
                ['kleene', [
                    ['expect', [[
                        ['ref', [ 'prec_7_op' ]],
                        ['ref', [ 'prec_6_expr' ]],
                    ]]],
                ]],
            ]]],
        ]],

        // precedence level 6: additive operators
        // prec_6_expr = prec_5_expr >> *(prec_6_op > prec_5_expr);
        'prec_6_expr' => ['rule', [
            'prec_6_expr',
            ['sequence', [[
                ['ref', [ 'prec_5_expr' ]],
                ['kleene', [
                    ['expect', [[
                        ['ref', [ 'prec_6_op' ]],
                        ['ref', [ 'prec_5_expr' ]],
                    ]]],
                ]],
            ]]],
        ]],

        // precedence level 5: multiplicative operators
        // prec_5_expr = prec_3_expr >> *(prec_5_op > prec_3_expr);
        'prec_5_expr' => ['rule', [
            'prec_5_expr',
            ['sequence', [[
                ['ref', [ 'prec_3_expr' ]],
                ['kleene', [
                    ['expect', [[
                        ['ref', [ 'prec_5_op' ]],
                        ['ref', [ 'prec_3_expr' ]],
                    ]]],
                ]],
            ]]],
        ]],

        // precedence level 3: unary operators
        // look at precedence 2 first
        // note: this does not work without distinct
        // unary_expr rule
        //
        // prec_3_expr = prec_2_expr | unary_expr;
        'prec_3_expr' => ['rule', [
            'prec_3_expr',
            ['alternative', [[
                ['ref', [ 'prec_2_expr' ]],
                ['ref', [ 'unary_expr' ]],
            ]]],
        ]],

        // unary_expr = prec_3_op > prec_2_expr;
        'unary_expr' => ['rule', [
            'unary_expr',
            ['expect', [[
                ['ref', [ 'prec_3_op' ]],
                ['ref', [ 'prec_2_expr' ]],
            ]]],
        ]],

        // precedence level 2: function call, array []
        // prec_2_expr =
        //      parser
        //      | directive
        //      | distinct_expr
        //      | id
        //      | quoted_string
        //      | quoted_char
        //      | paren_expr
        'prec_2_expr' => ['rule', [
            'prec_2_expr',
            ['alternative', [[
                ['ref', [ 'parsers' ]],
                ['ref', [ 'directives' ]],
                ['ref', [ 'distinct_expr' ]],
                ['ref', [ 'id' ]],
                ['ref', [ 'quoted_string' ]],
                ['ref', [ 'quoted_char' ]],
                ['ref', [ 'paren_expr' ]],
            ]]],
        ]],

        // identifier = *omit[(alpha | '_') >> *(alnum | '_') >> "::"]
        //   >> char_("a-z_") >> *char_("a-z_0-9");
        'identifier' => ['rule', [
            'identifier',
            ['sequence', [[
                ['kleene', [
                    ['omit', [
                        ['sequence', [[
                            ['alternative', [[
                                ['alpha', []],
                                ['lit', ['_']],
                            ]]],
                            ['kleene', [
                                ['alternative', [[
                                    ['alnum', []],
                                    ['lit', ['_']],
                                ]]],
                            ]],
                            ['lit', ['::']],
                        ]]],
                    ]],
                ]],
                ['char_set', ['a-z_']],
                ['kleene', [
                    ['char_set', ['a-z_0-9']],
                ]],
            ]]],
        ]],

        // id = identifier >> attr(token_type::op_identifier);
        'id' => ['rule', [
            'id',
            ['sequence', [[
                ['ref', ['identifier']],
                ['attr', ['identifier']],
            ]]],
        ]],

        // attr_id = identifier >> attr(token_type::op_attrid);
        'attr_id' => ['rule', [
            'attr_id',
            ['sequence', [[
                ['ref', ['identifier']],
                ['attr', ['attr_id']],
            ]]],
        ]],


        // paren_expr = lit('(') > attr(token_type::op_brace_open) > prec_14_expr > lit(')');
        'paren_expr' => ['rule', [
            'paren_8_expr',
            ['expect', [[
                ['char', ['(']],
                ['ref', [ 'prec_14_expr' ]],
                ['lit', [')']],
            ]]],
        ]],
        // parser = parsers >> !char_("a-z_") > argument_list

        'parser' => ['rule', [
            'parser',
            ['expect', [[
                ['distinct', [
                    ['char_set', ['a-z_']],
                    ['ref', [ 'parsers' ]],
                ]],
                ['ref', [ 'argument_list' ]],
            ]]],
        ]],

        // distinct_expr = distinct(char_("a-z_"))[distinct_directive]
        //      > '(' > prec_14_expr > ')' > '[' > prec_14_expr > ']'
        'distinct_expr' => ['rule', [
            'distinct_expr',
            ['expect', [[
                ['distinct', [
                    ['char_set', ['a-z_']],
                    ['ref', [ 'distinct_directive' ]],
                ]],
                ['lit', ['(']],
                ['ref', [ 'prec_14_expr' ]],
                ['lit', [')']],
                ['lit', ['[']],
                ['ref', [ 'prec_14_expr' ]],
                ['lit', [']']],
            ]]],
        ]],

        // directive = distinct(char_("a-z_"))[directives] > argument_list > '[' > prec_14_expr > ']'
        'directive' => ['rule', [
            'directive',
            ['expect', [[
                ['distinct', [
                    [ 'char_set', ['a-z_']],
                    [ 'ref', ['directives']]
                ]],
                ['ref', [ 'argument_list' ]],
                ['lit', [ '[' ]],
                ['ref', [ 'prec_14_expr' ]],
                ['lit', [ ']' ]],
            ]]],
        ]],

        // argument_list = -('(' > (quoted_char | quoted_string | id | number) % lit(',') > ')')
        'argument_list' => ['rule', [
            'argument_list',
            ['optional', [
                ['expect', [[
                    ['lit', ['(']],
                    ['list', [
                        ['alternative', [[
                            ['ref', [ 'quoted_char' ]],
                            ['ref', [ 'quoted_string' ]],
                            ['ref', [ 'attr_id' ]],
                            ['ref', [ 'number' ]],
                        ]]],
                        ['lit', [ ',' ]],
                    ]],
                    ['lit', [ ')' ]],
                ]]],
            ]],
        ]],

        // quoted_string = lexeme[char_('"') >> *(char_ - '"') >> char_('"')] >> attr(token_type::quoted_string)
        'quoted_string' => ['rule', [
            'quoted_string',
            ['sequence', [[
                ['lexeme', [
                    ['sequence', [[
                        ['char', [ '"' ]],
                        ['kleene', [
                            ['difference', [
                                ['char', []],
                                ['lit', [ '"' ]],
                            ]],
                        ]],
                        ['char', [ '"' ]],
                    ]]]
                ]],
                ['attr', [ 'quoted_string' ]],
            ]]],
        ]],

        // quoted_char = lexeme[string("'") >> char_ >> char_("'")] >> attr(token_type::quoted_char)
        'quoted_char' => ['rule', [
            'quoted char',
            ['sequence', [[
                ['lexeme', [
                    ['sequence', [[
                        ['char', [ "'" ]],
                        ['char', []],
                        ['char', [ "'" ]]
                    ]]]
                ]],
                ['attr', [ 'quoted_char' ]],
            ]]],
        ]],

        'ulong_long' => ['rule', [
            'ulong_long',
            ['sequence', [[
                ['raw', [
                    ['ulong_long', []],
                ]],
                ['attr', ['ulonglong']],
            ]]],
        ]],

        'ulong' => ['rule', [
            'ulong',
            ['sequence', [[
                ['raw', [
                    ['ulong', [ ]],
                ]],
                ['attr', [ 'ulong' ]],
            ]]],
        ]],

        'uint' => ['rule', [
            'uint',
            ['sequence', [[
                ['raw', [
                    ['uint', [ ]],
                ]],
                ['attr', [ 'uint' ]],
            ]]],
        ]],

        'ushort' => ['rule', [
            'ushort',
            ['sequence', [[
                ['raw', [
                    ['ushort', []],
                ]],
                ['attr', ['ushort']],
            ]]],
        ]],

        'longlong' => ['rule', [
            'longlong',
            ['sequence', [[
                ['raw', [
                    ['long_long', []],
                ]],
                ['attr', ['longlong']],
            ]]],
        ]],

        'long' => ['rule', [
            'long',
            ['sequence', [[
                ['raw', [
                    ['long', []],
                ]],
                ['attr', ['long']],
            ]]],
        ]],

        'int' => ['rule', [
            'int',
            ['sequence', [[
                ['raw', [
                    ['int', []],
                ]],
                ['attr', ['int']],
            ]]],
        ]],

        'short' => ['rule', [
            'short',
            ['sequence', [[
                ['raw', [
                    ['short', []],
                ]],
                ['attr', ['short']],
            ]]],
        ]],

        'bin' => ['rule', [
            'bin',
            ['sequence', [[
                ['raw', [
                    ['bin', []],
                ]],
                ['attr', ['bin']],
            ]]],
        ]],

        'oct' => ['rule', [
            'oct',
            ['sequence', [[
                ['raw', [
                    ['oct', []],
                ]],
                ['attr', ['oct']],
            ]]],
        ]],

        'hex' => ['rule', [
            'hex',
            ['sequence', [[
                ['raw', [
                    ['hex', []],
                ]],
                ['attr', ['hex']],
            ]]],
        ]],

        'bool' => ['rule', [
            'bool',
            ['sequence', [[
                ['raw', [
                    ['bool', []],
                ]],
                ['attr', ['bool']],
            ]]],
        ]],

        'float' => ['rule', [
            'float',
            ['sequence', [[
                ['raw', [
                    ['float', []],
                ]],
                ['attr', ['float']],
            ]]],
        ]],

        'double' => ['rule', [
            'double',
            ['sequence', [[
                ['raw', [
                    ['double', []],
                ]],
                ['attr', ['double']],
            ]]],
        ]],

        'long_double' => ['rule', [
            'long_double',
            ['sequence', [[
                ['raw', [
                    ['long_double', []],
                ]],
                ['attr', ['long_double']],
            ]]],
        ]],

        // number =  ushort_p | uint_p | ulong_p | ulong_long_p | short_p | int_p | long_p | long_long_p
        // | float_p | double_p | long_double_p | bool_p
        'number' => ['rule', [
            'number',
            ['alternative', [[
                ['ref', [ 'ushort' ]],
                ['ref', [ 'uint' ]],
                ['ref', [ 'ulong' ]],
                ['ref', [ 'ulonglong' ]],
                ['ref', [ 'short' ]],
                ['ref', [ 'int' ]],
                ['ref', [ 'long' ]],
                ['ref', [ 'longlong' ]],
                ['ref', [ 'float' ]],
                ['ref', [ 'double' ]],
                ['ref', [ 'longdouble' ]],
                ['ref', [ 'bool' ]],
            ]]],
        ]],
    ];

    public function __construct(Domain $domain, array $rules = null, string $source = null)
    {
        $this->domain = $domain;
        $this->rules = $rules ?? $this->rules;
        $this->domain->setDefinitions($this->rules);
        foreach ($this->rules as $name => $_) {
            $parser[$name] = $domain->getRule($name);
        }
        $parser = $parser['directives'];
        $parser->setSource('repeat');
        var_export($parser->parse(null));
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
