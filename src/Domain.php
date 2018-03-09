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

    public function __construct($inputEncoding, $internalEncoding)
    {
        $this->inputEncoding = $inputEncoding;
        $this->internalEncoding = $internalEncoding;
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
        $iterator = clone $this->internalEncoding->getIterator();
        $iterator->setData($arg, 0, strlen($arg), $this->noCaseSetting);
        return $iterator;
    }

    public function getInputIterator()
    {
        return $this->inputIterator = $this->inputIterator ?? $this->getInputEncoding()->getIterator();
    }

    public function getInputEncoding()
    {
        return $this->inputEncoding;
    }

    public function getInternalEncoding()
    {
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
        $this->inputIterator->setNoCase(array_pop($this->noCaseSetting) ?? false);
    }

    public function getNoCaseSetting()
    {
        return end($this->noCaseSetting);
    }
}
