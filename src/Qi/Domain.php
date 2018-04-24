<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Exception\NoRuleContextException;
use Mxc\Parsec\Exception\UnknownRuleException;
use Mxc\Parsec\Qi\NonTerminal\Grammar;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Support\NamedObject;

class Domain extends NamedObject
{
    protected $inputEncoding;
    protected $internalEncoding;
    protected $inputIterator;
    protected $parserManager;
    protected $rulePool = [];

    public function __construct($parserManager, $inputEncoding = 'UTF-8', $internalEncoding = 'UTF-8')
    {
        $this->inputEncoding = $inputEncoding;
        $this->internalEncoding = $internalEncoding;
        $this->inputIterator = $parserManager->get($inputEncoding);
        $this->parserManager = $parserManager;
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

    public function registerRule(Rule $r)
    {
        $this->rulePool[] = $r;
        end($this->rulePool);
        return key($this->rulePool);
    }

    public function getRule($ruleId)
    {
        $result = $this->rulePool[$ruleId];
        if (! $result instanceof Rule) {
            throw new UnknownRuleException(sprintf('Domain: No rule for ruleId %s.', $ruleId));
        }
        return $result;
    }

    public function enterContext(Grammar $context)
    {
        $this->contextStack[] = $context;
    }

    public function leaveContext()
    {
        $lastContext = array_pop($this->contextStack);
        return $lastContext;
    }

    public function getContext()
    {
        if (empty($this->contextStack)) {
            throw new NoRuleContextException('Domain: No rule context set.');
        }
        return end($this->contextStack);
    }

    public function __debugInfo()
    {
        return ['domain' => $this->name];
    }
}