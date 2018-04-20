<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\DelegatingParser;

class RuleReference extends DelegatingParser
{
    protected $name = null;
    protected $ruleId = null;
    protected $rule = null;
    protected $context = null;

    public function doParse(Domain $domain, $expectedValue, $attributeType, $skipper)
    {
        return $this->getRule()->parse($domain, $expectedValue, $attributeType, $skipper);
    }

    protected function getRule()
    {
        // rule references are allowed only in grammars
        if (! $this->rule) {
            // will throw if not in grammar
            $grammar = $this->domain->getContext();
            // if grammar does not know requested rule by name ...
            if (! $grammar->hasRule($this->name)) {
                // ... get it from domain's rulepool by ruleId ...
                $rule = $this->domain->getRule($this->ruleId());
                // ... and add it to grammar by name
                $grammar->addRule($rule);
                // @todo: What about using numeric rule id only
                // and having name only for informational purposes?
            }
            $this->rule = $grammar->getRule($this->name);
        }
        return $this->rule;
    }
}
