<?php

namespace Mxc\Parsec\Encoding;

use Mxc\Parsec\Exception\InvalidArgumentException;

class Scanner extends CharacterClassifier
{
    protected $data;
    protected $first;
    protected $last;
    protected $noCase;
    protected $binary;
    protected $cache;
    protected $invalidCache;
    protected $lastSize;

    protected $rew = [];

    public function __construct(string $s = '', $first = null, $last = null, bool $noCase = false, $binary = false)
    {
        $this->data = $s;

        $first = $first?: 0;
        $last = $last?: strlen($s);
        $this->noCase = $noCase;
        $this->binary = $binary;

        if (($last > strlen($s)) || ($last < 0) || ($first < 0) || (($first !== 0) && ($first >= $last))) {
            throw new InvalidArgumentException('Scanner: Invalid arguments.');
        }

        $this->first = $first;
        $this->last = $last;
    }

    public function parseString($string, &$attr)
    {
        $attr = '';
        while ($string->valid() && $this->valid()) {
            if ($this->noCase) {
                $s = $this->tolower($string->current());
                $c = $this->tolower($this->current());
            } else {
                $s = $string->current();
                $c = $this->current();
            }
            if ($s !== $c) {
                $attr = null;
                break;
            }
            $attr .= $this->current();
            $string->next();
            $this->next();
        }
        return (! $string->valid());
    }

    public function valid()
    {
        return $this->first < $this->last;
    }

    public function next()
    {
        $this->first += $this->lastSize;
    }

    public function key()
    {
        return $this->first;
    }

    public function &getData()
    {
        return $this->data;
    }

    public function setPos(int $first)
    {
        $this->first = $first;
        $this->invalidCache = $first;
    }

    public function getPos()
    {
        return $this->first;
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
        return $this;
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

    /**
     * Convert current character to lower case if noCase setting
     * is active.
     *
     * @return string current character with noCase setting applied
     */
    public function currentNoCase()
    {
        return $this->noCase ? $this->tolower($this->current()) : $this->current();
    }

    public function setBinary(bool $binary, int $size = 1)
    {
        $this->binary = $binary;
        $this->binarySize = $size;
        $this->invalidCache = $this->first;
    }
}
