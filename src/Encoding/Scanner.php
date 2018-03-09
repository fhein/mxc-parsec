<?php

namespace Mxc\Parsec\Encoding;

class Scanner extends CharacterClassifier
{
    protected $data;
    protected $first;
    protected $last;
    protected $noCase;
    protected $binary;
    protected $cache;
    protected $invalidCache;

    protected $rew = [];

    public function &getData()
    {
        return $this->data;
    }

    public function setPos(int $first)
    {
        $this->first = $first;
        $this->invalidCache = $first;
    }

    public function setData(string $data)
    {
        $this->data = $data;
        $this->first = 0;
        $this->last = strlen($data);
        $this->noCase = false;
        $this->binary = false;
        $this->invalidCache = 0;
        unset($this->cache);
        unset($this->rew);
    }

    public function try()
    {
        $this->rew[] = $this->first;
    }

    public function done(bool $accept)
    {
        if ($accept === true) {
            $this->accept();
        } else {
            $this->reject();
        }
        return $accept;
    }

    public function accept()
    {
        array_pop($this->rew);
        // if no further backtracking can happen
        if (! isset($this->rew[0])) {
            // reset the cache
            //print('Clearing cache. ');
            $this->invalidCache = 0;
            unset($this->cache);
        }
    }

    public function reject()
    {
        if (empty($this->rew)) {
            return false;
        }
        $this->first = array_pop($this->rew);
        ;
        return true;
    }

    public function setNoCase(bool $noCase)
    {
        $this->noCase = $noCase;
        $this->invalidCache = $this->first;
    }

    public function isNoCase()
    {
        return $this->noCase;
    }

    public function setBinary(bool $binary, int $size = 1)
    {
        $this->binary = $binary;
        $this->binarySize = $size;
        $this->invalidCache = $this->first;
    }
}
