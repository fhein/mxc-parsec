<?php

namespace Mxc\Parsec\Encoding;

class Encoding implements \IteratorAggregate
{

    protected $iterator;
    protected $classifier;

    public function __construct(CharacterClassifier $classifier, Scanner $iterator)
    {
        $this->classifier = $classifier;
        $this->iterator = $iterator;
    }

    public function getIterator()
    {
        return $this->iterator;
    }

    public function getClassifier()
    {
        return $this->classifier;
    }
}
