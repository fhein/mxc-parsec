<?php

namespace Mxc\Parsec\Encoding;

interface DecoderInterface
{
    public function getIterator(string &$s, int &$pos = 0, int &$last = 0);
    public function try(int &$pos);
    public function accept(int &$pos);
    public function reject(int &$pos);
}
