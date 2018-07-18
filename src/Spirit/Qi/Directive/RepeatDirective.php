<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\DelegatingParser;

class RepeatDirective extends DelegatingParser
{
    protected $gotMin;
    protected $gotMax;

    public function __construct(Domain $domain, string $uid, $subject, int $min = null, int $max = null)
    {
        parent::__construct($domain, $uid, $subject);

        // silently ignore additional args

        if ($min === null) {
            $this->gotMin = function ($i) {
                return true;
            };
            $this->gotMax = function ($i) {
                return true;
            };
        } else {
            $this->gotMin = function ($i) use ($min) {
                return $i >= $min;
            };
            if ($max === null || $max < $min) {
                $this->gotMax = function ($i) {
                    return true;
                };
            } else {
                $this->gotMax = function ($i) use ($max) {
                    return $i >= $max;
                };
            }
        }
    }

    public function doParse($skipper)
    {
        $attr = null;
        $assignment = null;
        $subject = $this->getSubject();
        for ($i = 0; ! ($this->gotMin)($i); $i++) {
            if (! $subject->parse($skipper)) {
                return false;
            }
            $this->attribute[] = $subject->getAttribute();
        }
        $save = $this->iterator->getPos();
        for (; ! ($this->gotMax)($i); $i++) {
            if (! $subject->parse($skipper)) {
                break;
            }
            $this->attribute[] = $subject->getAttribute();
            $save = $this->iterator->key();
        }
        $this->iterator->setPos($save);
        return true;
    }
}
