<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\DelegatingParser;

class RepeatDirective extends DelegatingParser
{
    protected $gotMin;
    protected $gotMax;

    public function __construct(Domain $domain, Parser $subject, int $min = null, int $max = null)
    {
        parent::__construct($domain, $subject);

        // silently ignore additional args

        if ($min === null) {
            $this->gotMin = function ($i) {
                return true;
            };
            $this->gotMax = function ($i) {
                return true;
            };
        } else {
            $this->gotMin = function ($i) {
                return $i >= $min;
            };
            if ($max === INF) {
                $this->gotMax = function ($i) {
                    return true;
                };
            } else {
                $this->gotMax = function ($i) {
                    return $i >= $max;
                };
            }
        }
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $attr = null;
        $assignment = null;
        $subject = $this->subject;
        for ($i = 0; ! $this->gotMin($i); $i++) {
            if (! $subject->parse($iterator, null, null, $skipper)) {
                return false;
            }
            $this->assignTo($subject->getAttribute(), 'array');
        }
        $save = $iterator->getPos();
        for (; ! $this->gotMax($i); $i++) {
            if (! $this->subject->parse($iterator, null, null, $skipper)) {
                break;
            }
            $this->assignTo($subject->getAttribute(), 'array');
            $save = $iterator->key();
        }
        $iterator->setPos($save);
        return true;
    }
}
