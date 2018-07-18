<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\DelegatingParser;

class RuleReference extends DelegatingParser
{
    protected $name = null;
    protected $ruleId = null;

    public function __construct(Domain $domain, string $uid, string $name, int $ruleId = 0)
    {
        $this->domain = $domain;
        $this->uid = $uid;
        $this->iterator = $domain->getInputIterator();
        $this->name = $name;
        $this->ruleId = $ruleId;
    }

    public function getSubject()
    {
        if (! $this->subject) {
            $this->subject = $this->domain->getRule($this->name);
            // // Allthough rule references can get created in any
            // // context, parsing of rule references is only allowed
            // // in a valid grammar context
            // // The next line will throw if not in a valid context
            // $grammar = $this->domain->getContext();
            // // if grammar does not know requested rule by name ...
            // if (! $grammar->hasRule($this->name)) {
            //     // ... get it from domain's rulepool by ruleId ...
            //     // ... and add it to grammar by name
            //     $grammar->addRule($this->domain->getRule($this->ruleId()));
            // }
            // $this->subject = $grammar->getRule($this->name);
        }
        return $this->subject;
    }
}
