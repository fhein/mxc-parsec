<?php

namespace Mxc\Parsec\Service;

use Mxc\Parsec\Attribute\Unused;
use Mxc\Parsec\Encoding\CharacterClassifier;
use Mxc\Parsec\Encoding\Utf8Decoder;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Qi\Auxiliary\AttrParser;
use Mxc\Parsec\Qi\Auxiliary\EoiParser;
use Mxc\Parsec\Qi\Auxiliary\EolParser;
use Mxc\Parsec\Qi\Auxiliary\EpsParser;
use Mxc\Parsec\Qi\Auxiliary\LazyParser;
use Mxc\Parsec\Qi\Auxiliary\LitParser;
use Mxc\Parsec\Qi\Auxiliary\RuleReference;
use Mxc\Parsec\Qi\Binary\BigBinDoubleParser;
use Mxc\Parsec\Qi\Binary\BigBinFloatParser;
use Mxc\Parsec\Qi\Binary\BigDWordParser;
use Mxc\Parsec\Qi\Binary\BigQWordParser;
use Mxc\Parsec\Qi\Binary\BigWordParser;
use Mxc\Parsec\Qi\Binary\BinDoubleParser;
use Mxc\Parsec\Qi\Binary\BinFloatParser;
use Mxc\Parsec\Qi\Binary\ByteParser;
use Mxc\Parsec\Qi\Binary\DWordParser;
use Mxc\Parsec\Qi\Binary\LittleBinDoubleParser;
use Mxc\Parsec\Qi\Binary\LittleBinFloatParser;
use Mxc\Parsec\Qi\Binary\LittleDWordParser;
use Mxc\Parsec\Qi\Binary\LittleQWordParser;
use Mxc\Parsec\Qi\Binary\LittleWordParser;
use Mxc\Parsec\Qi\Binary\QWordParser;
use Mxc\Parsec\Qi\Binary\WordParser;
use Mxc\Parsec\Qi\Char\AlnumParser;
use Mxc\Parsec\Qi\Char\AlphaParser;
use Mxc\Parsec\Qi\Char\BlankParser;
use Mxc\Parsec\Qi\Char\CharClassParser;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Qi\Char\CharRangeParser;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Char\CntrlParser;
use Mxc\Parsec\Qi\Char\DigitParser;
use Mxc\Parsec\Qi\Char\GraphParser;
use Mxc\Parsec\Qi\Char\LowerParser;
use Mxc\Parsec\Qi\Char\PrintParser;
use Mxc\Parsec\Qi\Char\PunctParser;
use Mxc\Parsec\Qi\Char\SpaceParser;
use Mxc\Parsec\Qi\Char\UpperParser;
use Mxc\Parsec\Qi\Char\XDigitParser;
use Mxc\Parsec\Qi\Directive\AsStringDirective;
use Mxc\Parsec\Qi\Directive\ExpectDirective;
use Mxc\Parsec\Qi\Directive\HoldDirective;
use Mxc\Parsec\Qi\Directive\LexemeDirective;
use Mxc\Parsec\Qi\Directive\MatchesDirective;
use Mxc\Parsec\Qi\Directive\NoCaseDirective;
use Mxc\Parsec\Qi\Directive\NoSkipDirective;
use Mxc\Parsec\Qi\Directive\OmitDirective;
use Mxc\Parsec\Qi\Directive\RawDirective;
use Mxc\Parsec\Qi\Directive\RepeatDirective;
use Mxc\Parsec\Qi\Directive\SkipDirective;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\NonTerminal\Grammar;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Qi\Numeric\BinaryParser;
use Mxc\Parsec\Qi\Numeric\BoolParser;
use Mxc\Parsec\Qi\Numeric\DoubleParser;
use Mxc\Parsec\Qi\Numeric\FalseParser;
use Mxc\Parsec\Qi\Numeric\FloatParser;
use Mxc\Parsec\Qi\Numeric\HexParser;
use Mxc\Parsec\Qi\Numeric\IntParser;
use Mxc\Parsec\Qi\Numeric\LongDoubleParser;
use Mxc\Parsec\Qi\Numeric\LongLongParser;
use Mxc\Parsec\Qi\Numeric\LongParser;
use Mxc\Parsec\Qi\Numeric\OctParser;
use Mxc\Parsec\Qi\Numeric\ShortParser;
use Mxc\Parsec\Qi\Numeric\TrueParser;
use Mxc\Parsec\Qi\Numeric\UIntParser;
use Mxc\Parsec\Qi\Numeric\ULongLongParser;
use Mxc\Parsec\Qi\Numeric\ULongParser;
use Mxc\Parsec\Qi\Numeric\UShortParser;
use Mxc\Parsec\Qi\Operator\AlternativeOperator;
use Mxc\Parsec\Qi\Operator\AndPredicate;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\ExpectOperator;
use Mxc\Parsec\Qi\Operator\KleeneStarOperator;
use Mxc\Parsec\Qi\Operator\ListOperator;
use Mxc\Parsec\Qi\Operator\NotPredicate;
use Mxc\Parsec\Qi\Operator\OptionalOperator;
use Mxc\Parsec\Qi\Operator\PermutationOperator;
use Mxc\Parsec\Qi\Operator\KleenePlusOperator;
use Mxc\Parsec\Qi\Operator\SequenceOperator;
use Mxc\Parsec\Qi\Operator\SequentialOrOperator;
use Mxc\Parsec\Qi\Repository\Auxiliary\AdvanceParser;
use Mxc\Parsec\Qi\Repository\Directive\DistinctDirective;
use Mxc\Parsec\Qi\String\StringParser;
use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Service\ParserFactory;
use Zend\ServiceManager\ServiceManager;
use Mxc\Parsec\Parser\SpiritQiParser;

class ParserManager extends ServiceManager
{
    protected $encodings =
    [
        'UTF-8' => Utf8Decoder::class,
    ];

    protected $services = [
        'config' => [
            'input_encoding'     => 'UTF-8',
            'internal_encoding'  => 'UTF-8',
            'output_encoding'    => 'UTF-8',
        ],
    ];

    protected $factories =
    [
        // auxiliary
        EolParser::class                => ParserFactory::class,
        AttrParser::class               => ParserFactory::class,
        EoiParser::class                => ParserFactory::class,
        EpsParser::class                => ParserFactory::class,
        LazyParser::class               => ParserFactory::class,
        LitParser::class                => ParserFactory::class,
        // binary
        ByteParser::class               => ParserFactory::class,
        WordParser::class               => ParserFactory::class,
        DWordParser::class              => ParserFactory::class,
        QWordParser::class              => ParserFactory::class,
        BigWordParser::class            => ParserFactory::class,
        BigDWordParser::class           => ParserFactory::class,
        BigQWordParser::class           => ParserFactory::class,
        LittleWordParser::class         => ParserFactory::class,
        LittleDWordParser::class        => ParserFactory::class,
        LittleQWordParser::class        => ParserFactory::class,
        BinDoubleParser::class          => ParserFactory::class,
        BigBinDoubleParser::class       => ParserFactory::class,
        LittleBinDoubleParser::class    => ParserFactory::class,
        BinFloatParser::class           => ParserFactory::class,
        BigBinFloatParser::class        => ParserFactory::class,
        LittleBinFloatParser::class     => ParserFactory::class,
        // char
        CharClassParser::class          => ParserFactory::class,
        CharParser::class               => ParserFactory::class,
        CharRangeParser::class          => ParserFactory::class,
        CharSetParser::class            => ParserFactory::class,
        '~' . CharClassParser::class    => NegatedCharParserFactory::class,
        '~' . CharParser::class         => NegatedCharParserFactory::class,
        '~' . CharRangeParser::class    => NegatedCharParserFactory::class,
        '~' . CharSetParser::class      => NegatedCharParserFactory::class,
        AlphaParser::class              => ParserFactory::class,
        AlnumParser::class              => ParserFactory::class,
        DigitParser::class              => ParserFactory::class,
        XDigitParser::class             => ParserFactory::class,
        CntrlParser::class              => ParserFactory::class,
        PrintParser::class              => ParserFactory::class,
        PunctParser::class              => ParserFactory::class,
        GraphParser::class              => ParserFactory::class,
        BlankParser::class              => ParserFactory::class,
        SpaceParser::class              => ParserFactory::class,
        UpperParser::class              => ParserFactory::class,
        LowerParser::class              => ParserFactory::class,

        // directive
        ExpectDirective::class          => ParserFactory::class,
        HoldDirective::class            => ParserFactory::class,
        LexemeDirective::class          => ParserFactory::class,
        MatchesDirective::class         => ParserFactory::class,
        NoCaseDirective::class          => ParserFactory::class,
        NoSkipDirective::class          => ParserFactory::class,
        OmitDirective::class            => ParserFactory::class,
        RawDirective::class             => ParserFactory::class,
        RepeatDirective::class          => ParserFactory::class,
        SkipDirective::class            => ParserFactory::class,
        AsStringDirective::class        => ParserFactory::class,
        // nonterminal
        Rule::class                     => ParserFactory::class,
        RuleReference::class            => ParserFactory::class,
        Grammar::class                  => ParserFactory::class,
        // numeric
        BinaryParser::class             => ParserFactory::class,
        BoolParser::class               => ParserFactory::class,
        TrueParser::class               => ParserFactory::class,
        FalseParser::class              => ParserFactory::class,
        HexParser::class                => ParserFactory::class,
        OctParser::class                => ParserFactory::class,
        ShortParser::class              => ParserFactory::class,
        IntParser::class                => ParserFactory::class,
        LongParser::class               => ParserFactory::class,
        LongLongParser::class           => ParserFactory::class,
        UShortParser::class             => ParserFactory::class,
        UIntParser::class               => ParserFactory::class,
        ULongParser::class              => ParserFactory::class,
        ULongLongParser::class          => ParserFactory::class,
        FloatParser::class              => ParserFactory::class,
        DoubleParser::class             => ParserFactory::class,
        LongDoubleParser::class         => ParserFactory::class,
        //operator
        AlternativeOperator::class      => ParserFactory::class,
        AndPredicate::class             => ParserFactory::class,
        DifferenceOperator::class       => ParserFactory::class,
        ExpectOperator::class           => ParserFactory::class,
        KleeneStarOperator::class           => ParserFactory::class,
        ListOperator::class             => ParserFactory::class,
        NotPredicate::class             => ParserFactory::class,
        OptionalOperator::class         => ParserFactory::class,
        PermutationOperator::class      => ParserFactory::class,
        KleenePlusOperator::class             => ParserFactory::class,
        SequenceOperator::class         => ParserFactory::class,
        SequentialOrOperator::class     => ParserFactory::class,
        // string
        StringParser::class             => ParserFactory::class,
        SymbolsParser::class            => ParserFactory::class,

        // Repository
        // directives
        DistinctDirective::class        => ParserFactory::class,
        // auxiliary
        AdvanceParser::class            => ParserFactory::class,

        SpiritQiParser::class           => ParserFactory::class,

        // non parsers
        Domain::class                   => DomainFactory::class,
    ];

    protected $invokables = [
        CharacterClassifier::class  => CharacterClassifier::class,
        Unused::class => Unused::class,
    ];

    //protected $shortNames = [];
    protected $aliases = [
        'eol'               => EolParser::class,
        'attr'              => AttrParser::class,
        'eoi'               => EoiParser::class,
        'eps'               => EpsParser::class,
        'lazy'              => LazyParser::class,
        'lit'               => LitParser::class,
        'ruleref'           => RuleReference::class,
        'byte'              => ByteParser::class,
        'big_word'          => BigWordParser::class,
        'big_dword'         => BigDWordParser::class,
        'big_qword'         => BigQWordParser::class,
        'little_word'       => LittleWordParser::class,
        'little_dword'      => LittleDWordParser::class,
        'little_qword'      => LittleQWordParser::class,
        'dword'             => DWordParser::class,
        'qword'             => QWordParser::class,
        'word'              => WordParser::class,
        'big_bin_double'    => BigBinDoubleParser::class,
        'big_bin_float'     => BigBinFloatParser::class,
        'little_bin_double' => LittleBinDoubleParser::class,
        'little_bin_float'  => LittleBinFloatParser::class,
        'bin_double'        => BinDoubleParser::class,
        'bin_float'         => BinFloatParser::class,
        'char_range'        => CharRangeParser::class,
        'char_set'          => CharSetParser::class,
        'char_class'        => CharClassParser::class,
        'char'              => CharParser::class,
        'alpha'             => AlphaParser::class,
        'alnum'             => AlnumParser::class,
        'xdigit'            => XDigitParser::class,
        'digit'             => DigitParser::class,
        'graph'             => GraphParser::class,
        'print'             => PrintParser::class,
        'punct'             => PunctParser::class,
        'blank'             => BlankParser::class,
        'cntrl'             => CntrlParser::class,
        'space'             => SpaceParser::class,
        'lower'             => LowerParser::class,
        'upper'             => UpperParser::class,
        'expect'            => ExpectDirective::class,
        'hold'              => HoldDirective::class,
        'lexeme'            => LexemeDirective::class,
        'matches'           => MatchesDirective::class,
        'no_case'           => NoCaseDirective::class,
        'no_skip'           => NoSkipDirective::class,
        'omit'              => OmitDirective::class,
        'raw'               => RawDirective::class,
        'repeat'            => RepeatDirective::class,
        'skip'              => SkipDirective::class,
        'as_string'         => AsStringDirective::class,
        'rule'              => Rule::class,
        'grammar'           => Grammar::class,
        'bin'               => BinaryParser::class,
        'bool'              => BoolParser::class,
        'true'              => TrueParser::class,
        'false'             => FalseParser::class,
        'hex'               => HexParser::class,
        'oct'               => OctParser::class,
        'ushort'            => UShortParser::class,
        'uint'              => UIntParser::class,
        'ulong_long'        => ULongLongParser::class,
        'ulong'             => ULongParser::class,
        'short'             => ShortParser::class,
        'int'               => IntParser::class,
        'long_long'         => LongLongParser::class,
        'long'              => LongParser::class,
        'float'             => FloatParser::class,
        'long_double'       => LongDoubleParser::class,
        'double'            => DoubleParser::class,
        'expect_o'          => ExpectOperator::class,
        'alternative'       => AlternativeOperator::class,
        'and'               => AndPredicate::class,
        'difference'        => DifferenceOperator::class,
        'kleene_star'       => KleeneStarOperator::class,
        'list'              => ListOperator::class,
        'not'               => NotPredicate::class,
        'optional'          => OptionalOperator::class,
        'permutation'       => PermutationOperator::class,
        'kleene_plus'       => KleenePlusOperator::class,
        'sequence'          => SequenceOperator::class,
        'sequential_or'     => SequentialOrOperator::class,
        'string'            => StringParser::class,
        'symbols'           => SymbolsParser::class,
        'distinct'          => DistinctDirective::class,
        'advance'           => AdvanceParser::class,
    ];

    protected $abstractFactories = [
        IteratorFactory::class
    ];

    protected $sharedByDefault = false;

    // all parsers with only one constructor parameter (which is $domain) can be shared
    protected $shared =
    [
        Unused::class                   => true,
        CharacterClassifier::class      => true,
        Domain::class                   => true,
        'input_encoding'                => true,
    ];

    public function __construct(array $options = [])
    {
        if (! empty($options)) {
            $this->services['config'] = $options;
        }
        $config = $this->services['config'];
        $inputEncoding = $config['input_encoding'] ?? null;
        $internalEncoding = $config['internal_encoding'] ?? null;
        if ($inputEncoding === null) {
            throw new InvalidArgumentException();
        }
        if ($internalEncoding === null) {
            throw new InvalidArgumentException();
        }
        $inputEncoding = $this->encodings[$config['input_encoding']];
        parent::__construct();

        $parserBuilder = new ParserBuilder($this);
        $this->services['parser_builder'] = $parserBuilder;

        $this->services['input_encoding'] = $this->get($config['input_encoding']);
        $this->services['internal_encoding'] = $this->get($config['internal_encoding']);
        $this->shortNames = array_flip($this->aliases);
    }

    public function __debugInfo()
    {
        return [
            'encodings'             => $this->encodings,
            'services'              => $this->services,
            'factories'             => $this->factories,
            'abstract_factories'    => $this->abstractFactories,
            'shared'                => $this->shared,
            'sharedByDefault'       => $this->sharedByDefault,
        ];
    }
}
