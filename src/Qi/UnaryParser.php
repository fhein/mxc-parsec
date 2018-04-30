<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Qi\Domain;

abstract class UnaryParser extends PrimitiveParser
{
    protected $subject;

    public function __construct(Domain $domain, $subject)
    {
        parent::__construct($domain);
        $this->subject = $subject;
    }

    public function __debugInfo()
    {
        return array_merge_recursive(
            parent::__debugInfo(),
            [
                'subject' => $this->subject ?? 'n/a',
            ]
        );
    }

    public function getSubject()
    {
        if (is_string($this->subject)) {
            $this->subject = $this->domain->getParser($this->subject);
        }
        return $this->subject;
    }
}
