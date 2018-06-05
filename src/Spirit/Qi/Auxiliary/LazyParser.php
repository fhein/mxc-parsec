<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Qi\Parser;

/**
 * Lazy Parser
 *
 * Wrapper parser which instantiates the wrapped parser on demand only
 *
 */
class LazyParser extends Parser
{
    /**
     * @var string $class class of wrapped parser
     */
    protected $class;

    /**
     * @var array $args construction arguments for wrapped parser
     */
    protected $args;

    /**
     * @var string $class wrapped parser instance
     */
    protected $subject;

    /**
     * Constructor
     *
     * @param Domain $domain
     * @param string $class
     * @param array ...$args
     */
    public function __construct(Domain $domain, string $uid, array $parserDefinition)
    {
        parent::__construct($domain, $uid);
        $this->lazy = $parserDefinition;
    }

    /**
     * Internal parser implementation
     *
     * {@inheritDoc}
     * @see \Mxc\Parsec\Qi\Parser::parse()
     */
    public function parse($skipper = null)
    {
        return $this->getSubject()->parse($skipper);
    }

    /**
     * Instantiate wrapped parser if not not done before
     *
     * {@inheritDoc}
     * @see \Mxc\Parsec\Qi\Parser::parse()
     */
    protected function getSubject()
    {
        if ($this->subject !== null) {
            return $this->subject;
        }
        if (is_string($this->lazy[0]) && class_exists($this->lazy[0]) && is_array($this->lazy[1])) {
            $this->subject = $this->domain->buildParser($this->lazy[0], $this->lazy[1]);
        } else {
            throw new InvalidArgumentException('Provided class is not a string or class does not exist.');
        }
        if (! $this->subject instanceof Parser) {
            throw new InvalidArgumentException('Instance of provided class is not a parser.');
        }
        return $this->subject;
    }

    public function __debugInfo()
    {
        return array_merge_recursive(
            parent::__debugInfo(),
            [
                'parser_definition' => $this->lazy ?? 'n/a',
                'subject' => $this->subject ?? 'n/a',
            ]
        );
    }

    public function getAttribute()
    {
        return $this->getSubject()->getAttribute();
    }
}
