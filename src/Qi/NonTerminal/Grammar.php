<?php

namespace Mxc\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Exception\UnknownRuleException;

class Grammar extends Parser
{
    protected $name;
    protected $rules;
    protected $startRule;

    public function __construct($domain, $name, array $rules = [], string $startRule = null)
    {
        parent::__construct($domain);
        $this->name = $name;
        $this->startRule = $startRule;
        $this->setRules($rules);
    }

    public function setRules(array $rules)
    {
        foreach ($rules as $rule) {
            $this->rules[$rule->getName()] = $rule;
        }
    }

    public function addRule(Rule $rule, bool $overwrite = false)
    {
        $name = $rule->getName();
        if (! $overwrite && $this->hasRule($name)) {
            return false;
        }
        $this->rules[$name] = $rule;
    }

    public function hasRule(string $name)
    {
        return isset($this->rules[$name]);
    }

    public function getRule(string $name)
    {
        if (! $this->hasRule($name)) {
            throw new UnknownRuleException(
                sprintf('%s: Unknown rule \'%s\'', $this->what(), $name)
            );
        }
    }

    public function parseRule($name, $iterator, $expectedValue, $attributeType, $skipper)
    {
        $rule = $this->getRule($name);
        if ($rule->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            $this->attribute = $rule->getAttribute();
            return true;
        }
        return false;
    }

    public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        return $this->parseRule($this->startRule, $iterator, $expectedValue, $attributeType, $skipper);
    }

    public function what()
    {
        return sprintf('%s (%s)', parent::what(), $this->name);
    }
}
