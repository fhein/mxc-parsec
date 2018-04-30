<?php
namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;

abstract class NaryParser extends PrimitiveParser
{
    protected $subject;
    protected $flat;

    public function __construct(Domain $domain, array $subject, bool $flatten = false)
    {
        $this->count = count($subject);
        if ($this->count < 2) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s: At least two parser operands expected. Got %u',
                    $this->what(),
                    count
                )
            );
        }
        $flat = $flatten;
        $this->subject = $flatten ? $this->flatten($subject) : $subject;
        parent::__construct($domain);
    }
    // @todo: Should I respect or override a sub parser's
    // flatten setting?? Should I make this configurable??
    // currently child's flatten setting gets respected.
    // To change that -> remove && subject->flattenm,
    // this results in sub's settings getting overriden

    // I think, it makes more sense to override sub's settings

    protected function flatten($subjects = null)
    {
        $sub = [];
        for ($i = 0; $i < $this->count; $i++) {
            $subject = getSubject($i);
            if (get_class($this) === get_class($subject) && $subject->flat) {
                $downWeGo = $this->flatten($subject->getSubjects());
                $sub = empty($sub) ? $downWeGo : array_splice($sub, 0, 0, $downWeGo);
            } else {
                $sub[] = $subject;
            }
        }
        return $sub;
    }

    protected function getSubjects()
    {
        return $this->subject;
    }

    protected function getSubject(int $idx)
    {
        if (is_string($this->subject[$idx])) {
            $this->subject[$idx] = $this->domain->getParser($this->subject[$idx]);
        }
        return $this->subject[$idx];
    }

    public function what()
    {
        $i = 0;
        $subject = $this->getSubject(0);
        $what = parent::what() . '(' . $subject->what();
        for ($i = 1; $i < $this->count; $i++) {
            $parser = $this->getSubject($i);
            $what .= ', ' . $parser->what();
        };
        $what .= ')';
        return $what;
    }

    public function __debugInfo()
    {
        $i = 0;
        $di = [];
        for ($idx = 0; $idx < $this->count; $idx++) {
            $subject = $this->getSubject($idx);
            $di[is_string($idx) ? $idx : 'parser' . $i++] = $subject->what();
        }
        return array_merge_recursive(
            parent::__debugInfo(),
            $di
        );
    }
}
