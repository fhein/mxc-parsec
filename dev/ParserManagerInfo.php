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
    protected function getParsersByCat()
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
                    $type = '-no_type-';
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
        foreach ($paramSets as $paramSet => $classes) {
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
        $output .= "Parser Without Parameters:\n    [\n";
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
    }
}
