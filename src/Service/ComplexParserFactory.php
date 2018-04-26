<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;

class ComplexParserFactory implements AbstractFactoryInterface
{
    protected $parserDefinitions;
    protected $parserManager;

    protected $parsers = [
        'eol'               => 'Mxc\Parsec\Qi\Auxiliary\EolParser',
        'attr'              => 'Mxc\Parsec\Qi\Auxiliary\AttrParser',
        'eoi'               => 'Mxc\Parsec\Qi\Auxiliary\EoiParser',
        'eps'               => 'Mxc\Parsec\Qi\Auxiliary\EpsParser',
        'lazy'              => 'Mxc\Parsec\Qi\Auxiliary\LazyParser',
        'lit'               => 'Mxc\Parsec\Qi\Auxiliary\LitParser',
        'ref'               => 'Mxc\Parsec\Qi\Auxiliary\RuleReference',
        'byte'              => 'Mxc\Parsec\Qi\Binary\ByteParser',
        'big_word'          => 'Mxc\Parsec\Qi\Binary\BigWordParser',
        'big_dword'         => 'Mxc\Parsec\Qi\Binary\BigDWordParser',
        'big_qword'         => 'Mxc\Parsec\Qi\Binary\BigQWordParser',
        'little_word'       => 'Mxc\Parsec\Qi\Binary\LittleWordParser',
        'little_dword'      => 'Mxc\Parsec\Qi\Binary\LittleDWordParser',
        'little_qword'      => 'Mxc\Parsec\Qi\Binary\LittleQWordParser',
        'dword'             => 'Mxc\Parsec\Qi\Binary\DWordParser',
        'qword'             => 'Mxc\Parsec\Qi\Binary\QWordParser',
        'word'              => 'Mxc\Parsec\Qi\Binary\WordParser',
        'big_bin_double'    => 'Mxc\Parsec\Qi\Binary\BigBinDoubleParser',
        'big_bin_float'     => 'Mxc\Parsec\Qi\Binary\BigBinFloatParser',
        'little_bin_double' => 'Mxc\Parsec\Qi\Binary\LittleBinDoubleParser',
        'little_bin_float'  => 'Mxc\Parsec\Qi\Binary\LittleBinFloatParser',
        'bin_double'        => 'Mxc\Parsec\Qi\Binary\BinDoubleParser',
        'bin_float'         => 'Mxc\Parsec\Qi\Binary\BinFloatParser',
        'char_range'        => 'Mxc\Parsec\Qi\Char\CharRangeParser',
        'char_set'          => 'Mxc\Parsec\Qi\Char\CharSetParser',
        'char_class'        => 'Mxc\Parsec\Qi\Char\CharClassParser',
        'char'              => 'Mxc\Parsec\Qi\Char\CharParser',
        'alpha'             => 'Mxc\Parsec\Qi\Char\AlphaParser',
        'alnum'             => 'Mxc\Parsec\Qi\Char\AlnumParser',
        'xdigit'            => 'Mxc\Parsec\Qi\Char\XDigitParser',
        'digit'             => 'Mxc\Parsec\Qi\Char\DigitParser',
        'graph'             => 'Mxc\Parsec\Qi\Char\GraphParser',
        'print'             => 'Mxc\Parsec\Qi\Char\PrintParser',
        'punct'             => 'Mxc\Parsec\Qi\Char\PunctParser',
        'blank'             => 'Mxc\Parsec\Qi\Char\BlankParser',
        'cntrl'             => 'Mxc\Parsec\Qi\Char\CntrlParser',
        'space'             => 'Mxc\Parsec\Qi\Char\SpaceParser',
        'lower'             => 'Mxc\Parsec\Qi\Char\LowerParser',
        'upper'             => 'Mxc\Parsec\Qi\Char\UpperParser',
        'expect_directive'  => 'Mxc\Parsec\Qi\Directive\ExpectDirective',
        'hold'              => 'Mxc\Parsec\Qi\Directive\HoldDirective',
        'lexeme'            => 'Mxc\Parsec\Qi\Directive\LexemeDirective',
        'matches'           => 'Mxc\Parsec\Qi\Directive\MatchesDirective',
        'no_case'           => 'Mxc\Parsec\Qi\Directive\NoCaseDirective',
        'no_skip'           => 'Mxc\Parsec\Qi\Directive\NoSkipDirective',
        'omit'              => 'Mxc\Parsec\Qi\Directive\OmitDirective',
        'raw'               => 'Mxc\Parsec\Qi\Directive\RawDirective',
        'repeat'            => 'Mxc\Parsec\Qi\Directive\RepeatDirective',
        'skip'              => 'Mxc\Parsec\Qi\Directive\SkipDirective',
        'as_string'         => 'Mxc\Parsec\Qi\Directive\AsStringDirective',
        'rule'              => 'Mxc\Parsec\Qi\NonTerminal\Rule',
        'grammar'           => 'Mxc\Parsec\Qi\NonTerminal\Grammar',
        'bin'               => 'Mxc\Parsec\Qi\Numeric\BinaryParser',
        'bool'              => 'Mxc\Parsec\Qi\Numeric\BoolParser',
        'true'              => 'Mxc\Parsec\Qi\Numeric\TrueParser',
        'false'             => 'Mxc\Parsec\Qi\Numeric\FalseParser',
        'hex'               => 'Mxc\Parsec\Qi\Numeric\HexParser',
        'oct'               => 'Mxc\Parsec\Qi\Numeric\OctParser',
        'ushort'            => 'Mxc\Parsec\Qi\Numeric\UShortParser',
        'uint'              => 'Mxc\Parsec\Qi\Numeric\UIntParser',
        'ulong_long'        => 'Mxc\Parsec\Qi\Numeric\ULongLongParser',
        'ulong'             => 'Mxc\Parsec\Qi\Numeric\ULongParser',
        'short'             => 'Mxc\Parsec\Qi\Numeric\ShortParser',
        'int'               => 'Mxc\Parsec\Qi\Numeric\IntParser',
        'long_long'         => 'Mxc\Parsec\Qi\Numeric\LongLongParser',
        'long'              => 'Mxc\Parsec\Qi\Numeric\LongParser',
        'float'             => 'Mxc\Parsec\Qi\Numeric\FloatParser',
        'long_double'       => 'Mxc\Parsec\Qi\Numeric\LongDoubleParser',
        'double'            => 'Mxc\Parsec\Qi\Numeric\DoubleParser',
        'expect'            => 'Mxc\Parsec\Qi\Operator\ExpectOperator',
        'alternative'       => 'Mxc\Parsec\Qi\Operator\AlternativeOperator',
        'and'               => 'Mxc\Parsec\Qi\Operator\AndPredicate',
        'difference'        => 'Mxc\Parsec\Qi\Operator\DifferenceOperator',
        'kleene'            => 'Mxc\Parsec\Qi\Operator\KleeneOperator',
        'list'              => 'Mxc\Parsec\Qi\Operator\ListOperator',
        'not'               => 'Mxc\Parsec\Qi\Operator\NotPredicate',
        'optional'          => 'Mxc\Parsec\Qi\Operator\OptionalOperator',
        'permutation'       => 'Mxc\Parsec\Qi\Operator\PermutationOperator',
        'plus'              => 'Mxc\Parsec\Qi\Operator\PlusOperator',
        'sequence'          => 'Mxc\Parsec\Qi\Operator\SequenceOperator',
        'sequential_or'     => 'Mxc\Parsec\Qi\Operator\SequentialOrOperator',
        'string'            => 'Mxc\Parsec\Qi\String\StringParser',
        'symbols'           => 'Mxc\Parsec\Qi\String\SymbolsParser',
        'distinct'          => 'Mxc\Parsec\Qi\Repository\Directive\DistinctDirective',
        'advance'           => 'Mxc\Parsec\Qi\Repository\Auxiliary\AdvanceParser',
    ];

    protected function getParserDefinitions(ContainerInterface $container)
    {
        if ($this->parserDefinitions === null) {
            $this->parserDefinitions = $container->get('parser_definitions');
        }
        return $this->parserDefinitions;
    }

    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\AbstractFactoryInterface::canCreate()
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return null !== $this->getParserDefinitions($container)[$requestedName];
    }

    protected function prepareOptions(array $options)
    {
        foreach ($options as $key => $option) {
            if (is_array($option)) {
                $options[$key] = $this->getParser($option) ?? $this->prepareOptions($option);
            }
        }
        return $options;
    }

    protected function getParser(array $definition)
    {
        $parser = $definition[0];
        $count = count($definition);
        if (! is_string($parser) || $count > 2) {
            return null;
        }
        $parser = $this->parsers[$parser] ?? ($this->parserManager->has($parser) ? $parser : null);
        if ($parser === null) {
            return null;
        }
        $options = $this->prepareOptions($definition[1]) ?? [];
        if (! is_array($options)) {
            return null;
        }
        return $this->parserManager->build($parser, $options);
    }

    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $parserDefinition = $this->getParserDefinitions($container)[$requestedName];
        $this->parserManager = $container->get('parser_manager');
        $this->container = $container;
        if (is_array($parserDefinition)) {
            $parser = $this->getParser($parserDefinition);
        }
        if ($parser === null) {
            throw new \Exception('Invalid parser definition table.');
        }
        print_r($this->parserStore);
        return $parser;
    }
}
