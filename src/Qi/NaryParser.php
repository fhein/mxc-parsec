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
        $count = count($subject);
        if ($count < 2) {
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
        foreach ($subjects as $subject) {
            if (get_class($this) === get_class($subject) && $subject->flat) {
                $downWeGo = $this->flatten($subject->getSubject());
                $sub = empty($sub) ? $downWeGo : array_splice($sub, 0, 0, $downWeGo);
            } else {
                $sub[] = $subject;
            }
        }
        return $sub;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function what()
    {
        $i = 0;
        foreach ($this->subject as $parser) {
            $what = parent::what() . '(' . $parser->what();
            break;
        }
        foreach (array_slice($this->subject, 1) as $parser) {
            $what .= ', ' . $parser->what();
        };
        $what .= ')';
        return $what;
    }

    public function __debugInfo()
    {
        $i = 0;
        $di = [];
        foreach ($this->subject as $idx => $parser) {
            $di[is_string($idx) ? $idx : 'parser' . $i++] = $parser->what();
        }
        return array_merge_recursive(
            parent::__debugInfo(),
            $di
        );
    }
}
