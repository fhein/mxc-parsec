<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Encoding\CharacterClassifier;
use Mxc\Parsec\Encoding\Utf8Decoder;
use Mxc\Parsec\Qi\UnusedAttribute;

class Domain
{

    protected $inputEncoding;
    protected $internalEncoding;

    protected $characterClassifier;
    protected $inputClassifier;

    protected $unusedAttribute;

    protected $inputIterator;

    protected $services;

    protected $noCaseSetting = [];

    protected $parserManager;

    public function __construct($parserManager, $inputEncoding = 'UTF-8', $internalEncoding = 'UTF-8')
    {
        $this->inputEncoding = $inputEncoding;
        $this->internalEncoding = $internalEncoding;
        $this->inputIterator = $parserManager->get($inputEncoding);
        $this->parserManager = $parserManager;
    }

    public function getUnusedAttribute()
    {
        return $this->unusedAttribute ?? $this->unusedAttribute = $this->parserManager->get(UnusedAttribute::class);
    }

    public function getCharacterClassifier()
    {
        return $this->characterClassifier ??
            $this->characterClassifier = $this->parserManager->get(CharacterClassifier::class);
    }

    public function getInternalIterator(string $arg)
    {
        $iterator = $this->parserManager->build($this->internalEncoding);
        $iterator->setData($arg, 0, strlen($arg), $this->noCaseSetting);
        return $iterator;
    }

    public function getInputIterator()
    {
        return $this->inputIterator;
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
