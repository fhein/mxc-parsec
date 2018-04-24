<?php

namespace Mxc\Parsec\Encoding;

use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Exception\BadMethodCallException;

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
    protected $noCaseStack = [];
    protected $positionStack = [];

    public function __construct(string $s = '', $first = null, $last = null, bool $noCase = false, $binary = false)
    {
        $this->setData($s);
        return;
        $this->data = $s;

        $first = $first ?? 0;
        $last = $last ?? strlen($s);
        $this->noCase = $noCase;
        $this->binary = $binary;

        if (($last > strlen($s)) || ($last < 0) || ($first < 0) || (($first !== 0) && ($first > $last))) {
            throw new InvalidArgumentException('Scanner: Invalid arguments.');
        }

        $this->first = $first;
        $this->last = $last;
    }

    /**
     * Considering the current $noCase setting compare two characters.
     * Return true, if characters are equal. Return false otherwise.
     *
     * @param string $c1
     * @param string $c2
     * @return boolean
     */
    public function compareChar(string $c1, string $c2 = null)
    {
        if ($c2 === null) {
            return true;
        }
        if ($this->noCase) {
            return $this->tolower($c1) === $this->tolower($c2);
        }
        return $c1 === $c2;
    }

    /**
     * Get the current character without advancing the iterator position.
     * If an optional character argument is supplied, the current character
     * gets compared with the supplied character (considering the current
     * noCase setting). Returns
     *
     * If no character is supplied to compare with, return the current
     * character and true.
     *
     * @param unknown $attr     return
     * @param string $char      optional: expected value
     * @return mixed            character or false
     */
    public function parseChar(string $char = null)
    {
        if (! $this->valid()) {
            return false;
        }
        $c = $this->current();
        if ($char !== null && ! $this->compareChar($c, $char)) {
            return false;
        }
        return $c;
    }

    public function getSubStr($start, $length)
    {
        return substr($this->data, $start, $length);
    }

    public function parseString($string, &$attr)
    {
        $attr = '';
        while ($string->valid() && $this->valid()) {
            $c = $this->current();
            if (! $this->compareChar($string->current(), $c)) {
                $attr = null;
                break;
            }
            $attr .= $this->current();
            $string->next();
            $this->next();
        }
        // if $string is not valid here we have a match
        return (! ($string->valid()));
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

    public function getInputSize()
    {
        return $this->last - $this->first;
    }

    public function setData(string $data)
    {
        $this->data = $data;
        $this->first = 0;
        $this->last = strlen($data);
        $this->noCase = false;
        $this->binary = false;
        $this->invalidCache = 0;
        $this->noCaseStack = [];
        unset($this->cache);
        unset($this->positionStack);
    }

    public function try()
    {
        $this->positionStack[] = $this->first;
        return $this;
    }

    public function done(bool $accept)
    {
        $accept ? $this->accept() : $this->reject();
        return $accept;
    }

    public function accept()
    {
        if (empty($this->positionStack)) {
            throw new BadMethodCallException('accept() called without try().');
        }
        array_pop($this->positionStack);
        // if no further backtracking can happen
        if (empty($this->positionStack)) {
            // reset the cache
            //print('Clearing cache. ');
            $this->invalidCache = 0;
            unset($this->cache);
        }
    }

    public function reject()
    {
        if (empty($this->positionStack)) {
            throw new BadMethodCallException('reject() called without try().');
        }
        $this->first = array_pop($this->positionStack);
    }

    public function setNoCase(bool $noCase = null)
    {
        if ($noCase === true) {
            $this->noCaseStack[] = $this->noCase;
            $this->noCase = true;
            $this->invalidCache = $this->first;
            return;
        }
        $this->noCase = empty($this->noCaseStack) ? false : array_pop($this->noCaseStack);
    }

    public function isNoCase()
    {
        return $this->noCase;
    }

    /**
     * Get character character at current position. Convert to lower case
     * if noCase is active.
     *
     * @return string current character with noCase setting applied
     */
    public function currentNoCase()
    {
        return $this->getNoCaseComparableCharacter($this->current());
    }

    public function getNoCaseComparableCharacter(string $c)
    {
        return $this->noCase ? $this->tolower($c) : $c;
    }

    public function setBinary(bool $binary, int $size = 1)
    {
        $this->binary = $binary;
        $this->binarySize = $size;
        $this->invalidCache = $this->first;
    }
}
