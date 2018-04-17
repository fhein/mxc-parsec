<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Encoding\CharacterClassifier;

class Domain
{

    protected $inputEncoding;
    protected $internalEncoding;

    protected $characterClassifier;
    protected $inputClassifier;

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
        $iterator = $this->getInputIterator();
        $iterator->setData($source);
        return $iterator;
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

    public function buildParser(string $class, array $options = [])
    {
        return $this->parserManager->build($class, $options);
    }

    public function __debugInfo()
    {
        return ['Domain'];
    }
}
