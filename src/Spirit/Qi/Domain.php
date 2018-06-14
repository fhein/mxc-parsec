<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Exception\NoRuleContextException;
use Mxc\Parsec\Exception\UnknownRuleException;
use Mxc\Parsec\Qi\NonTerminal\Grammar;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Support\NamedObject;
use Mxc\Parsec\Service\ParserBuilder;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class Domain extends NamedObject
{
    protected $inputEncoding;
    protected $internalEncoding;
    protected $inputIterator;
    protected $parserManager;
    protected $rulePool = [];
    protected $log = [];

    public function __construct($parserManager, $inputEncoding = 'UTF-8', $internalEncoding = 'UTF-8')
    {
        $this->inputEncoding = $inputEncoding;
        $this->internalEncoding = $internalEncoding;
        $this->parserManager = $parserManager;
        $this->inputIterator = $parserManager->get($inputEncoding);
        $this->parserBuilder = $parserManager->get(ParserBuilder::class);
    }

    public function log($cmd)
    {
        $this->log[] = $cmd;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function getInternalIterator(string $arg)
    {
        return $this->parserManager->build($this->internalEncoding, [$arg]);
    }

    public function getInputIterator()
    {
        return $this->inputIterator;
    }

    public function buildParser(string $class, array $options = null)
    {
        return $this->parserManager->build($class, $options);
    }

    public function getParser(string $definition)
    {
        return $this->parserBuilder->getParser($definition);
    }

    public function setDefinitions($definitions)
    {
        return $this->parserBuilder->setDefinitions($definitions);
    }

    public function getRule(string $definition)
    {
        return $this->parserBuilder->getRule($definition);
    }

    public function __debugInfo()
    {
        return ['domain' => $this->name];
    }
}
