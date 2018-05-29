<?php
$x = [
    "sequential_or", [
        [
            ["xdigit", []],
            ["alpha", []],
            ["hold", [ "list", []]
            ]
        ]
    ]
];


$x = ["sequential_or", [[
        ["xdigit",[]],
        ["alpha",[]],
        ["hold", [
            ["list", [
                ["sequence", [[[
                    ["alpha",[]],
                    ["alnum",[]],
                ]]]],
                ["lower",[]],
            ]],
        ]],
    ]],
];

$x = ["alternative", [[
        ["eol", []],
        ["xdigit", []],
        ["distinct", [[
            ["alnum", []], ,
            ["lower", []],
        ]]],
        ["true", []],
        ["sequential_or", [[
            ["alpha", []],
            ["graph", []],
        ]]],
        ["eol", []], ]]];




{"category":"Rules &amp; Grammar", {
    {"type":"rule_type", },
    {"type":"grammar_type", },
    {"type":"ruleref_type", },
},
{"category":"Directives", {
    {"type":"matches_type",},
    {"type":"omit_type",},
    {"type":"lexeme_type",},
    {"type":"skip_type",},
    {"type":"no_skip_type",},
    {"type":"hold_type",},
    {"type":"as_string_type",},
    {"type":"no_case_type",},
    {"type":"expect_d_type",},
    {"type":"raw_type",},
    {"type":"repeat_type",},

},
{"category":"Predicates", {
{"type":"not_type",},
{"type":"and_type",},
},
{"category":"Char Classifiers", {
{"type":"digit_type",},
{"type":"xdigit_type",},
{"type":"alpha_type",},
{"type":"alnum_type",},
{"type":"lower_type",},
{"type":"graph_type",},
{"type":"upper_type",},
{"type":"punct_type",},
{"type":"print_type",},
{"type":"cntrl_type",},
{"type":"space_type",},
{"type":"blank_type",},
},
{"category":"Char Parsers", {
{"type":"char_range_type",},
{"type":"char_set_type",},
{"type":"char_class_type",},
{"type":"char_type",},
{"type":"lit_type",},
},
{"category":"String Parsers", {
{"type":"string_type",},
{"type":"symbols_type",},
{"type":"lit_type",},
},
{"category":"Boolean Parsers", {
{"type":"bool_type",},
{"type":"true_type",},
{"type":"false_type",},
},
{"category":"Decimal Parsers", {
{"type":"all_null_type",},
{"type":"ushort_type",},
{"type":"uint_type",},
{"type":"ulong_type",},
{"type":"ulong_long_type",},
{"type":"short_type",},
{"type":"int_type",},
{"type":"long_type",},
{"type":"long_long_type",},
{"type":"lit_type",},
},
{"category":"Floating Point Parsers", {
{"type":"float_type",},
{"type":"double_type",},
{"type":"long_double_type",},
{"type":"lit_type",},
},
{"category":"Non-decimal Parsers", {
{"type":"bin_type",},
{"type":"oct_type",},
{"type":"hex_type",},
{"type":"lit_type",},
},
{"category":"Binary Parsers", {
{"type":"byte_type",},
{"type":"word_type",},
{"type":"dword_type",},
{"type":"qword_type",},
{"type":"bin_float_type",},
{"type":"bin_double_type",},
{"type":"big_word_type",},
{"type":"big_dword_type",},
{"type":"big_qword_type",},
{"type":"big_bin_float_type",},
{"type":"big_bin_double_type",},
{"type":"little_word_type",},
{"type":"little_dword_type",},
{"type":"little_qword_type",},
{"type":"little_bin_float_type",},
{"type":"little_bin_double_type",},
{"type":"lit_type",},
},
{"category":"Unary Operators", {
{"type":"kleene_type",},
{"type":"plus_type",},
},
{"category":"Binary operators", {
{"type":"expect_type",},
{"type":"difference_type",},
{"type":"list_type",},
},
{"category":"N-ary Operators", {
{"type":"alternative_type",},
{"type":"sequence_type",},
{"type":"permutation_type",},
{"type":"sequential_or_type",},
},
{"category":"Auxiliary Parsers", {
{"type":"eol_type",},
{"type":"attr_type",},
{"type":"eoi_type",},
{"type":"eps_type",},
{"type":"lazy_type",},
{"type":"advance_type",},
