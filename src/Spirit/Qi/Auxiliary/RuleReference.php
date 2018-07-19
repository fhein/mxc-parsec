<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\DelegatingParser;

class RuleReference extends DelegatingParser
{
    protected $name = null;
    protected $ruleId = null;

    public function __construct(Domain $domain, string $uid, string $name, string $ruleId)
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
            $this->subject = $this->domain->getParser($this->ruleId);
        }
        return $this->subject;
    }
}
