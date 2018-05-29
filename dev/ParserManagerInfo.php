<?php
namespace Mxc\Dev\Parsec;

use Mxc\Parsec\Service\ParserManager;

use ReflectionClass;
use Mxc\Parsec\Qi\NaryParser;
use Mxc\Parsec\Qi\BinaryParser;
use Mxc\Parsec\Qi\UnaryParser;
use Mxc\Parsec\Qi\PredicateParser;
use Mxc\Parsec\Qi\DelegatingParser;
use Mxc\Parsec\Qi\PrimitiveParser;
use Mxc\Parsec\Qi\PreSkipper;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Numeric\BoolParser;
use Mxc\Parsec\Qi\Numeric\TrueParser;
use Mxc\Parsec\Qi\Numeric\FalseParser;
use Mxc\Parsec\Qi\Auxiliary\LitParser;
use Mxc\Parsec\Qi\NonTerminal\Grammar;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Qi\Auxiliary\LazyParser;

class ParserManagerInfo extends ParserManager
{
    protected $colours = [
        'Auxiliary' => 'AUX',
        'Binary' => 'BIN',
        'Char' => 'CHAR',
        'Directive' => 'DIR',
        'NonTerminal' => 'NT',
        'Numeric' => 'NUM',
        'Operator' => 'OP',
        'String' => 'STR',
    ];

    protected $messages = [
        'EolParser' => [ 'end of line', 'Matches line separators. No attribute returned.'],
        'AttrParser' => [ 'attribute',  'Succeeds allways and returns %1.'],
        'EoiParser' => [ 'end of input', 'Matches end of input. No attribute returned.'],
        'EpsParser' => [ 'epsilon', 'Zero length match. No attribute returned.'],
        'LazyParser' => [ 'lazy',  'Lazily instantiate %1'],
        'LitParser' => [ 'literal', 'Match literal char, string or number. No attribute returned.' ],
        // binary
        'ByteParser' => [ 'byte', 'Match byte (native endian).' ],
        'WordParser' => [ 'word', 'Match word (native endian).' ],
        'DWordParser' => [ 'double word', 'Match double word (native endian byte order).' ],
        'QWordParser' => [ 'quad word', 'Match quad word (native endian byte order).' ],
        'BigWordParser' => [ 'word (BE)', 'Match word (big endian byte order).' ],
        'BigDWordParser' => [ 'double word (BE)', 'Match double word (big endian byte order).' ],
        'BigQWordParser' => [ 'quad word (BE)', 'Match quad word (big endian byte order).' ],
        'LittleWordParser' => [ 'word (LE)', 'Match word (little endian byte order).' ],
        'LittleDWordParser' => [ 'double word (LE)', 'Match double word (little endian byte order).' ],
        'LittleQWordParser' => [ 'quad word (LE)', 'Match quad word (little endian byte order).' ],
        'BinDoubleParser' => [ 'binary double', 'Match binary encoded double (native endian byte order).' ],
        'BigBinDoubleParser' => [ 'binary double (BE)', 'Match binary encoded double (big endian byte order).' ],
        'LittleBinDoubleParser' => [ 'binary double (LE)', 'Match binary encoded double (little endian byte order).' ],
        'BinFloatParser' => ['binary float', 'Match binary encoded float (native endian byte order).' ],
        'BigBinFloatParser' => [ 'binary float (BE)', 'Match binary encoded float (big endian byte order).' ],
        'LittleBinFloatParser' => ['binary float (LE)', 'Match binary encoded float (little endian byte order).' ],
        // char
        'CharClassParser' => [ 'char class', 'Match characters belonging to a particular character class.'],
        'CharParser' => [ 'char', 'Match all or a particular character.'],
        'CharRangeParser' => [ 'char range', 'Match all characters in a given range.'],
        'CharSetParser' => [ 'charset', 'Match all characters in a given character set (POSIX style definition).'],
        'AlphaParser' => [ 'alpha', 'Match alphabetic characters.' ],
        'AlnumParser' => [ 'alnum', 'Match alphanumeric characters.'],
        'DigitParser' => [ 'digit', 'Match digits.'],
        'XDigitParser' => [ 'xdigit', 'Match hexadecimal digits.'],
        'CntrlParser' => [ 'cntrl', 'Match control characters.'],
        'PrintParser' => [ 'print', 'Match printable characters.'],
        'PunctParser' => [ 'punct', 'Match punctuation characters'],
        'GraphParser' => [ 'graph', 'Match graphical characters.'],
        'BlankParser' => [ 'blank', 'Match blanks without line breaks.'],
        'SpaceParser' => [ 'space', 'Match blanks and line breaks.'],
        'UpperParser' => [ 'upper', 'Match upper case characters.'],
        'LowerParser' => [ 'lower', 'Match lower case characters'],

        // directive
        'ExpectDirective' => [ 'expect', 'Throw exception if %1 fails.' ],
        'HoldDirective' => [ 'hold', 'Restore attribute if %1 fails.' ],
        'LexemeDirective' => [ 'lexeme', 'Turn of skipper for embraced parser. Does preskipping.' ],
        'MatchesDirective' => [ 'matches', 'Allways succeeds. Returns success of embraced as attribute.' ],
        'NoCaseDirective' => [ 'ignore case', 'Ignore case.' ],
        'NoSkipDirective' => [ 'do not skip', 'Turn off skipper.'],
        'OmitDirective' => [ 'omit' , 'Do not return an attribute, if embraced parser succeeds.' ],
        'RawDirective' => [ 'raw' , 'Transduction Parser. Returns the covered text from source,' .
            ' if embraced parser succeeds.'],
        'RepeatDirective' => [ 'repeat' , 'Repeat embraced parser.'],
        'SkipDirective' => [ 'enable skipper' , 'Reenable or change skipper.'],
        'AsStringDirective' => [ 'as string', 'Convert attribute of embraced parser to string.' ],
        // nonterminal
        'Rule' => [ 'rule', 'Named parser with attribute type casting capabilities.'],
        'RuleReference' => [ 'rule reference', 'Reference to a rule.'],
        'Grammar' => [ 'grammar', 'Container for rules with an explicitly named start rule.'],
        // numeric
        'BinaryParser' => [ 'binary number', 'Succeeds on binary numbers.'],
        'BoolParser' => [ 'boolean', 'Succeeds on boolean values.'],
        'TrueParser' => [ 'true',  'Succeeds on the string \'true\'.'],
        'FalseParser' => [ 'false', 'Succeeds on the string \'false\'.'],
        'HexParser' => [ 'hex number', 'Succeeds on hexadecimal numbers.'],
        'OctParser' => [ 'octal number', 'Succeeds on octal numbers.' ],
        'ShortParser' => [ 'short int', 'Succeeds on numbers which match type \'short\'.'],
        'IntParser' => [ 'int', 'Succeeds on numbers which match type \'int\'.'],
        'LongParser' => [ 'long', 'Succeeds on numbers which match type \'long\'.'],
        'LongLongParser' => [ 'long long', 'Succeeds on numbers which match type \'longlong\'.'],
        'UShortParser' => [ 'unsigned short', 'Succeeds on numbers which match type \'unsigned short\'.'],
        'UIntParser' => [ 'unsigned int', 'Succeeds on numbers which match type \'unsigned int\'.'],
        'ULongParser' => [ 'unsigned long', 'Succeeds on numbers which match type \'unsigned long\'.'],
        'ULongLongParser' => [ 'unsigned long long', 'Succeeds on numbers which match type \'unsigned long long\'.'],
        'FloatParser' => [ 'float', 'Succeeds on numbers which match type \'float\'.'],
        'DoubleParser' => [ 'double', 'Succeeds on numbers which match type \'double\'.'],
        'LongDoubleParser' => [ 'long double', 'Succeeds on numbers which match type \'long double\'. '
            . 'Only available on 64 bit PHP versions.' ],
        //operator
        'AlternativeOperator' => [ 'one of', 'Sequentially tries embraced parsers. '
            . 'Succeeds, if currently tried parser succeeds.'],
        'AndPredicate' => [ 'matches (unused attr)', 'Succeeds if embraced parser succeeds. No attribute returned.'],
        'DifferenceOperator' => [ '%1 without %2', 'Succeeds if first parser succeeds and second parser fails. '
            . 'Returns attribute of first parser.'],
        'ExpectOperator' => [ 'expect', 'Throw exception if second embraced parser fails.'],
        'KleeneStarOperator' => [ '0 or more of %1', 'Kleene Star Operator.'
            . ' Succeeds if embraced parser succeeds 0 or more times.'
            . ' Returns an array of attributes of embraced parsers.'],
        'ListOperator' => [ 'list of %1 separated by %2', 'Succeeds, if first parser succeeds once.'
            . ' If second parser succeeds, first parser is tried again.'
            . ' Returns an array of attributes of first parser.'],
        'NotPredicate' => [ 'not', 'Succeeds if embraced parser fails.'
            . ' Fails, if embraced parser succeeds. Does not alter position in source text.'
            . ' Does not return an attribute.'],
        'OptionalOperator' => [ 'optional', 'Always succeeds. Returns an attribute of type \'optional\','
            . ' which does not get set if embraced parser fails.'],
        'PermutationOperator' => [ 'permutation', 'Permutation -> todo'],
        'PlusOperator' => [ 'one or more of %1', 'Succeeds if embraced parser succeeds at least one time.'
            . ' Returns an array of attributes.'],
        'SequenceOperator' => [ 'sequence', 'Executes embraced parsers on by one. Succeeds if all parsers succeed.'
            . ' Returns a tuple of attributes.'],
        'SequentialOrOperator' => [ 'sequential or', 'Sequential or -> todo.'],
        // string
        'StringParser' => [ 'string', 'Matches a given string.'],
        'SymbolsParser' => [ 'keywords', 'Given a map of keywords to associated results this parser succeeds'
            . ' if the input matches the keywords and returns the associated result as attribute.' ],

        // Repository
        // directives
        'DistinctDirective' => [ 'distinct', 'todo'],
        // auxiliary
        'AdvanceParser' => [ 'advance', 'Advances the input iterator.' ],
    ];

    /**
     * This function generates a list of all parsers categorized by
     * parser class
     * @return string[]
     */
    public function getParsersByClss()
    {
        $di = [];
        $tagged = [];
        foreach (array_keys($this->factories) as $name) {
            if ($name[0] === '~' || $name === Domain::class) {
                continue;
            }
            $rc = new ReflectionClass($name);
            $classes = [
                BinaryParser::class => 'Binary Parsers',
                NaryParser::class => 'Nary Parsers',
                UnaryParser::class => 'Unary Parsers',
                PredicateParser::class => 'Unary Parsers',
                DelegatingParser::class => 'Unary Parsers',
                PrimitiveParser::class => 'Primitive Parsers',
                PreSkipper::class => 'Primitive Parsers',
            ];
            $dname = substr(strrchr($name, '\\'), 1);
            foreach ($classes as $class => $idx) {
                if ($tagged[$dname]) {
                    continue;
                }
                if ($name === Rule::class || $name === Grammar::class) {
                    if (! $tagged[$dname]) {
                        $di['NonTerminal'][] = $dname;
                        $tagged[$dname] = true;
                    }
                } elseif ($name === BoolParser::class
                    || $name === TrueParser::class
                    || $name === FalseParser::class
                    || $name === LitParser::class
                    ) {
                        $di['Primitive Parsers'][] = $dname;
                        $tagged[$dname] = true;
                } elseif ($name === LazyParser::class) {
                    $di['Unary Parsers'][] = $dname;
                    $tagged[$dname] = true;
                } elseif ($rc->isSubClassOf($class)) {
                    $di[$idx][] = $dname;
                    $tagged[$dname] = true;
                }
            }
            if (! $tagged[$dname]) {
                $di['Unknown'] = $dname;
                $tagged[$dname] = true;
            }
        }
        return $di;
    }
    /**
     * This function generates a list of all parsers categorized by
     * parser category
     * @return string[]
     */
    public function getParsersByCat()
    {
        $di = [];
        $tagged = [];
        foreach (array_keys($this->factories) as $name) {
            if ($name[0] === '~' || $name === Domain::class) {
                continue;
            }
            $rc = new ReflectionClass($name);
            $ns = $rc->getNamespaceName();
            $dname = substr(strrchr($name, '\\'), 1);
            $dns = substr(strrchr($ns, '\\'), 1);
            $di[$dns][] = $dname;
        }
        return $di;
    }

    public function findShareableParsers()
    {
        $di = [];
        foreach (array_keys($this->factories) as $name) {
            if ($name[0] === '~' || $name === Domain::class) {
                continue;
            }
            $rc = new ReflectionClass($name);
            $m = $rc->getMethod('__construct');
            $params = $m->getNumberOfParameters();
            if ($params === 1) {
                $dname = substr(strrchr($name, '\\'), 1);
                $di[] = $dname;
            }
        }
        return $di;
    }

    public function getFQCN()
    {
        $di = [];
        foreach (array_keys($this->factories) as $name) {
            if ($name[0] === '~' || $name === Domain::class) {
                continue;
            }
            $dname = substr(strrchr($name, '\\'), 1);
            $di[$dname] = $name;
        }
        return $di;
    }

    public function getParserConstructionParams()
    {
        $di = [];
        foreach (array_keys($this->factories) as $name) {
            if ($name[0] === '~' || $name === Domain::class) {
                continue;
            }
            $rc = new ReflectionClass($name);
            $constructor = $rc->getConstructor();
            $params = $constructor->getParameters();
            array_shift($params);
            $dname = substr(strrchr($name, '\\'), 1);
            foreach ($params as $param) {
                $name = $param->getName();
                if ($param->hasType()) {
                    $type = $param->getType();
                } else {
                    $type = '-any_type-';
                }
                $optional = $param->isOptional() ? 'optional' : 'mandatory';
                $default = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
                if ($default !== null) {
                    $default = var_export($default, true);
                    if (substr($default, 0, 7) === 'array (') {
                        $default = substr($default, 8);
                        $default = '['.substr($default, 9, strlen($default) - 2).']';
                    }
                    $default = ', default: '.$default;
                } elseif ($optional === 'optional') {
                    $default = ', default: null';
                }
                $optional .= $default;
                $di[$dname][] = sprintf('%s $%s <%s>', $type, $name, $optional);
            }
        }
        return $di;
    }

    public function getParsersByParameterSet()
    {
        $date = getdate();
        $date = $date['mday'].". ".$date['month']." ". $date['year'];
        $classes = $this->getParserConstructionParams();
        foreach ($classes as $name => $params) {
            $key = '';
            foreach ($params as $param) {
                $key .= '        '.$param."\n";
            }
            $key = "    [\n".$key."    ]\n";
            $paramSets[$key][] = $name;
        }
        $output = "List of all parsers by parameter set ($date)\n";
        $blockType = 'blocktype_';
        $i = 0;
        foreach ($paramSets as $paramSet => $classes) {
            $output .= "------------------------------------------------------------------\n";
            $output .= $blockType . $i++ . "\n";
            $output .= "------------------------------------------------------------------\n";
            $output .= "Parameter Set:\n";
            $output .= $paramSet;
            $output .= "Used By:\n    [\n";
            foreach ($classes as $class) {
                $output .= '        '.$class."\n";
            }
            $output .= "    ]\n";
        }
        $output .= "------------------------------------------------------------------\n";

        $noParams = $this->findShareableParsers();
        $output .= $blockType . $i++ . "\n";
        $output .= "------------------------------------------------------------------\n";
        $output .= "No Parameters\n";
        $output .= "------------------------------------------------------------------\n";
        $output .= "    [\n";
        foreach ($noParams as $parser) {
            $output .= '        ' . $parser . "\n";
        }
        $output .= "    ]\n";
        return $output;
    }

    public function getParsersByCategory()
    {
        $date = getdate();
        $date = $date['mday'].". ".$date['month']." ". $date['year'];
        $di = $this->getParsersByCat();
        $output = "List of all parsers by functional category ($date)\n";
        foreach ($di as $category => $parsers) {
            $output .= "------------------------------------------------------------------\n";
            $output .= 'Category \'' . $category . "'\n";
            $output .= "    [\n";
            foreach ($parsers as $parser) {
                $output .= '        ' . $parser . "\n";
            }
            $output .= "    ]\n";
        }
        return $output;
    }

    public function getParsersByClass()
    {
        $date = getdate();
        $date = $date['mday'].". ".$date['month']." ". $date['year'];
        $di = $this->getParsersByClss();
        $output = "List of all parsers by technical classification ($date)\n";
        foreach ($di as $class => $parsers) {
            $output .= "------------------------------------------------------------------\n";
            $output .= 'Parser Class \'' . $class . "'\n";
            $output .= "    [\n";
            if (! empty($parsers)) {
                foreach ($parsers as $parser) {
                    $output .= '        ' . $parser . "\n";
                }
            }
            $output .= "    ]\n";
        }
        return $output;
    }

    public function getParsersFQCN()
    {
        $date = getdate();
        $date = $date['mday'].". ".$date['month']." ". $date['year'];
        $di = $this->getFQCN();
        asort($di);
        $output = "List of parser fully qualified class names ($date)\n";
        $output .= "------------------------------------------------------------------\n";
        foreach ($di as $class => $parser) {
            $output .= sprintf("% -25s: %s\n", $class, $parser);
        }
        return $output;
    }

    public function getAllParsersJSObject()
    {
        $types = [
            'Primitive Parsers' => 'primitive',
            'Unary Parsers' => 'unary',
            'Binary Parsers' => 'binary',
            'Nary Parsers'  => 'nary',
            'NonTerminal'   => 'nonterminal',
            'no arguments'  => 'noargs'
        ];
        $di = $this->getParsersByClss();
        $fqcn = $this->getFQCN();
        $aliases = array_flip($this->aliases);

        foreach ($di as $class => $parsers) {
            $type = $types[$class];
            print_r($class."\n");
            if (is_array($parsers)) {
                foreach ($parsers as $parser) {
                    $obj[$aliases[$fqcn[$parser]]] = $type;
                }
            };
        }
        ksort($obj);
        return 'var allParsers = '. str_replace('},', "},\n", json_encode($obj)) . ';';
    }

    public function getBlocklyBlocks()
    {
        $di = $this->getParsersByClss();
        $gui = $this->getParsersByCat();
        foreach ($gui as $cat => $parsers) {
            foreach ($parsers as $parser) {
                $colour[$parser] = $this->colours[$cat];
            }
        }

        foreach ($di as $class => $parsers) {
            foreach ($parsers as $parser) {
                switch ($class) {
                    case 'No Arguments':
                        $blocks[] = [
                            'type' => strtolower($parser),
                            'message0' => $this->messages[$parser][0],
                            'inputsInline' => true,
                            'previousStatement' => null,
                            'nextStatement' => null,
                            'colour' => '%{BKY_'. $colour[$parser] .'_HUE}',
                            'tooltip' => $this->messages[$parser][1],
                            'helpUrl' => '',
                        ];
                        break;

                    case 'Primitive Parsers':
                        $blocks[] = [
                            'type' => strtolower($parser),
                            'message0' => $this->messages[$parser][0],
                            'args0' => [
                                [
                                    'type' => 'input_value',
                                    'name' => 'param',
                                    'check' => [
                                        'char',
                                        'charset',
                                    ],
                                ],
                            ],
                            'inputsInline' => true,
                            'previousStatement' => null,
                            'nextStatement' => null,
                            'colour' => '%{BKY_'. $colour[$parser] .'_HUE}',
                            'tooltip' => $this->messages[$parser][1],
                            'helpUrl' => '',
                        ];
                        break;
                    case 'Unary Parsers':
                        $blocks[] = [
                            'type' => 'plusoperator',
                            'message0' => $this->messages[$parser][0],
                            'args0' => [
                                [
                                    'type' => 'input_statement',
                                    'name' => 'param',
                                    'check' => 'parser',
                                ],
                            ],
                            'previousStatement' => null,
                            'nextStatement' => null,
                            'colour' => '%{BKY_'. $colour[$parser] .'_HUE}',
                            'tooltip' => $this->messages[$parser][1],
                            'helpUrl' => '',
                        ];
                        break;
                    case 'Binary Parsers':
                        $blocks[] = [
                            'type' => strtolower($parser),
                            'message0' => $this->messages[$parser][0],
                            'args0' => [
                                [
                                    'type' => 'input_statement',
                                    'name' => 'paramLeft',
                                    'check' => 'parser',
                                ],
                                [
                                    'type' => 'input_statement',
                                    'name' => 'paramRight',
                                    'check' => 'parser',
                                ]
                            ],
                            'previousStatement' => null,
                            'nextStatement' => null,
                            'colour' => '%{BKY_'. $colour[$parser] .'_HUE}',
                            'tooltip' => $this->messages[$parser][1],
                            'helpUrl' => '',
                        ];
                        break;
                    case 'NonTerminal':
                        break;
                    case 'Nary Parsers':
                        $blocks[] = [
                            'type' => strtolower($parser),
                            'message0' => $this->messages[$parser][0],
                            'args0' => [
                                [
                                    'type' => 'input_statement',
                                    'name' => 'param',
                                    'check' => 'parser',
                                ],
                            ],
                            'previousStatement' => null,
                            'nextStatement' => null,
                            'colour' => '%{BKY_'. $colour[$parser] .'_HUE}',
                            'tooltip' => $this->messages[$parser][1],
                            'helpUrl' => '',
                        ];
                        break;
                }
            }
        }
        return json_encode($blocks, JSON_PRETTY_PRINT);
    }

    public function createArrayFromJson(string $json)
    {
        return json_decode($json, JSON_OBJECT_AS_ARRAY);
    }

    public function updateInfoFiles()
    {
        $output = $this->getParsersByClass();
        file_put_contents(__DIR__.'/../doc/ParsersByTechnicalClassification.txt', $output);
        $output = $this->getParsersByCategory();
        file_put_contents(__DIR__.'/../doc/ParsersByFunctionalCategory.txt', $output);
        $output = $this->getParsersByParameterSet();
        file_put_contents(__DIR__.'/../doc/ParsersByParameterSet.txt', $output);
        $output = $this->getParsersFQCN();
        file_put_contents(__DIR__.'/../doc/ParsersWithFQCN.txt', $output);
        $output = $this->getAllParsersJSObject();
        file_put_contents(__DIR__.'/../doc/AllParsersJSObject.txt', $output);
    }

    public function getParsers()
    {
        return array_keys($this->aliases);
    }
}
