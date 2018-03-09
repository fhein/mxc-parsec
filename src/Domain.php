<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Encoding\CharacterClassifier;
use Mxc\Parsec\Encoding\Utf8Decoder;
use Mxc\Parsec\Qi\UnusedAttribute;

class Domain
{

    protected $inputEncoding;
    protected $internalEncoding;

    protected $internalClassifier;
    protected $inputClassifier;

    protected $unusedAttribute;

    protected $inputIterator;

    protected $services;

    protected $noCaseSetting = [];

    public function __construct($encodings, $options)
    {
        $this->encodings = $encodings;
        $this->inputEncoding = $options['inputEncoding'];
        $this->internalEncoding = $options['internalEncoding'];
    }

    public function getUnusedAttribute()
    {
        return ($this->unusedAttribute = $this->unusedAttribute ?? new UnusedAttribute());
    }

    public function getinternalClassifier()
    {
        return ($this->internalClassifier !== null) ?:
            $this->internalClassifier = $this->getInternalEncoding()->getClassifier();
    }

    public function getinputClassifier()
    {
        return ($this->inputClassifier !== null) ?: $this->inputClassifier = $this->getInputEncoding()->getClassifier();
    }

    public function getInternalIterator(string $arg)
    {
        $iterator = clone $this->getInternalEncoding()->getIterator();
        $iterator->setData($arg, 0, strlen($arg), $this->noCaseSetting);
        return $iterator;
    }

    public function getInputIterator()
    {
        return $this->inputIterator = $this->inputIterator?:
            $this->inputIterator = $this->getInputEncoding()->getIterator();
    }

    public function getInputEncoding()
    {
        if (is_string($this->inputEncoding)) {
            $this->inputEncoding = $this->encodings->get($this->inputEncoding);
        }
        return $this->inputEncoding;
    }

    public function getInternalEncoding()
    {
        if (is_string($this->internalEncoding)) {
            $this->internalEncoding = $this->encodings->get($this->internalEncoding);
        }
        return $this->internalEncoding;
    }

    public function setSource(string $source)
    {
        $this->noCaseSetting = [];
        $this->getInputIterator()->setData($source);
    }

    public function setNoCase(bool $state = true)
    {
        $this->noCaseSetting[] = $this->inputIterator->isNoCase();
        $this->inputIterator->setNoCase($state);
    }

    public function restoreNoCaseSetting()
    {
        $ch = count($this->noCaseSetting) > 0 ? array_pop($this->noCaseSetting) : false;
        $this->inputIterator->setNoCase($ch);
    }

    public function getNoCaseSetting()
    {
        $count = count($this->noCaseSetting);
        return $count > 0 ? $this->noCaseSetting[$count - 1] : false;
    }
}
