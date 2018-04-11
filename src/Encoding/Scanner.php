<?php

namespace Mxc\Parsec\Encoding;

use IntlChar;
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
    protected function compareChar(string $c1, string $c2)
    {
        if ($this->noCase) {
            return IntlChar::tolower($c1) === IntlChar::tolower($c2);
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
            $attr = null;
            return false;
        }
        return $c;
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
        if (empty($this->rew)) {
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
