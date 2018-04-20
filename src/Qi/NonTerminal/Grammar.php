<?php

namespace Mxc\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Exception\UnknownRuleException;
use Mxc\Parsec\Qi\NaryParser;
use Mxc\Parsec\Exception\InvalidArgumentException;

class Grammar extends NaryParser
{
    protected $name;
    protected $rules;
    protected $startRule;

    public function __construct($domain, $name, array $rules = [], string $startRule = null)
    {
        parent::__construct($domain, $rules);
        $this->name = $name;
        $this->startRule = $startRule;
        foreach ($this->subject as $idx => $rule) {
            $name = $rule->getName();
            if (isset($this->subject[$name])) {
                throw new InvalidArgumentException(
                    sprintf('%s: Duplicate rule name \'%s\'', $this->what(), $name)
                );
            }
            $this->subject[$rule->getName()] = $rule;
            unset($this->subject[$idx]);
        }
    }

    public function addRule(Rule $rule, bool $overwrite = false)
    {
        $name = $rule->getName();
        if (! $overwrite && $this->hasRule($name)) {
            throw new InvalidArgumentException(
                sprintf('%s: Duplicate rule name \'%s\'', $this->what(), $name)
            );
        }
        $this->subject[$name] = $this->flat ? $this->flatten([$rule])[0] : $rule;
    }

    public function hasRule(string $name)
    {
        return isset($this->subject[$name]);
    }

    public function getRule(string $name)
    {
        if (! $this->hasRule($name)) {
            throw new UnknownRuleException(
                sprintf('%s: Unknown rule \'%s\'', $this->what(), $name)
            );
        }
    }

    public function parseRule(Rule $rule, $iterator, $expectedValue, $attributeType, $skipper)
    {
        if ($rule->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            $this->attribute = $rule->getAttribute();
            return true;
        }
        return false;
    }

    public function doParse()
    {
        $this->domain->enterContext($this);
        $result = $this->parseRule($this->getRule($this->startRule));
        $this->domain->leaveContext($this);
        return $result;
    }

//     public function what()
//     {
//         $i = 0;
//         foreach ($this->subject as $name => $rule) {
//             $what = $this->shortClassName() . '['. $this->name . '] (' . $name . ' => '. $rule->what();
//             break;
//         }
//         foreach (array_slice($this->subject, 1) as $name => $rule) {
//             $what .= ', ';
//             $what .= $name. ' => ' .  $rule->what();
//         };
//         $what .= ')';
//         return $what;
//     }
}
