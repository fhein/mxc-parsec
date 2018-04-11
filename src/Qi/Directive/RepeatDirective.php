<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\ParserDelegator;

class RepeatDirective extends ParserDelegator
{
    protected $gotMin;
    protected $gotMax;

    public function __construct(Domain $domain, Parser $subject, ...$args)
    {
        parent::__construct($domain, $subject, $args);

        $min = isset($this->args[0]) ? $this->args[0] : null;
        $max = isset($this->args[1]) ? $this->args[1] : null;

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
            if (! $subject->parse($iterator, $expectedValue, $attributeType, $skipper)) {
                return false;
            }
            $this->assignTo($subject->getAttribute(), $attributeType);
        }
        $save = $iterator->getPos();
        for ($i = 0; ! $this->gotMax($i); $i++) {
            if (! $this->subject->parse($iterator, $expectedValue, $attributeType, $skipper)) {
                break;
                $save = $iterator->key();
            }
            $this->assignTo($subject->getAttribute(), $attributeType);
        }
        $iterator->setPos($save);
        return true;
    }
}
