<?php

namespace Mxc\Parsec\Qi\NonTerminal;

use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Exception\UnknownRuleException;
use Mxc\Parsec\Qi\PreSkipper;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Qi\Domain;

class Grammar extends PreSkipper
{
    protected $rules;
    protected $startRule;

    public function __construct(Domain $domain, string $uid, string $name, array $rules = [], string $startRule = null)
    {
        parent::__construct($domain, $uid);
        $this->subject = $rules;
        $this->name = $name;
        $this->startRule = $startRule;
        $this->count = count($rules);
        foreach ($this->subject as $idx => $rule) {
            if (is_string($rule)) {
                $rule = $this->domain->getParser($rule);
            }
            $name = $rule->getName();
            if (isset($this->subject[$name])) {
                throw new InvalidArgumentException(
                    sprintf('%s: Duplicate rule name \'%s\'.', $this->what(), $name)
                );
            }
            $this->subject[$name] = $rule;
            unset($this->subject[$idx]);
        }
    }

    public function addRule(Rule $rule, bool $overwrite = false)
    {
        $name = $rule->getName();
        if (! $overwrite && $this->hasRule($name)) {
            throw new InvalidArgumentException(
                sprintf('%s: Duplicate rule name \'%s\'.', $this->what(), $name)
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
                sprintf('%s: Unknown rule \'%s\'.', $this->what(), $name)
            );
        }
        if (is_string($this->subject[$name])) {
            $this->subject[$name] = $this->domain->getParser($this->subject[$name]);
        }
        return $this->subject[$name];
    }

    public function doParse($skipper)
    {
        $this->domain->enterContext($this);

        $rule = $this->getRule($this->startRule);
        if ($rule->parse($skipper)) {
//            print("Result: true\n");
            $x = $rule->getAttribute();
//            var_export($x);
            $this->assignTo($x, $this->attributeType);
            $this->domain->leaveContext($this);
            return true;
        }
 //       print("Result: false\n");
        $this->domain->leaveContext($this);
        return false;
    }

    public function what()
    {
        $i = 0;
        $rules = [];
        foreach ($this->subject as $name => $_) {
            $rules[] = $this->getRule($name)->what();
        }
        $rules = implode(', ', $rules);

        $what = parent::what() . '(' . $rules . ')';
        return $what;
    }

    public function __debugInfo()
    {
        $i = 0;
        $di = [];
        foreach ($this->subject as $name => $_) {
            $subject = $this->getRule($name);
            $di[is_string($name) ? $name : 'parser' . $i++] = $subject->what();
        }
        return array_merge_recursive(
            parent::__debugInfo(),
            $di
        );
    }
}
