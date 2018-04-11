<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Exception\BadMethodCallException;
use Mxc\Parsec\Domain;

abstract class ParserDelegator extends Parser
{
    const     MSG = '%s: subject parser not initialized.';

    protected $subject = null;
    protected $exception = BadMethodCallException::class;

    public function __construct(Domain $domain, Parser $subject = null)
    {
        parent::__construct($domain);
        $this->subject = $subject;
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if ($this->subject === null) {
            throw new $this->exception(sprintf(self::MSG, $this->what()));
        }
        return $this->subject->parse($iterator, $expectedValue, $attributeType, $skipper);
    }

    public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        return $iterator->try()->done($this->doParse($iterator, $expectedValue, $attributeType, $skipper));
    }

    public function getAttribute()
    {
        if ($this->subject === null) {
            throw new BadMethodCallException(sprintf(self::MSG, $this->what()));
        }
        return $this->subject->getAttribute();
    }

    public function what()
    {
        return $this->subject !== null ? [ parent::what(), $this->subject->what()] : parent::what();
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
}
