<?php

namespace Mxc\Parsec\Encoding;

use Mxc\Benchmark\Parsec\Benchmark;

// use Maxence\Benchmark\Utf8\Utf8Decoder;

require '../autoload.php';

// $d = new Utf8Decoder();
// $s = "\xC0\x80\x80";
// var_dump($d->validate_utf8_decode($s));

$b = new Benchmark;
$test = file_get_contents(__DIR__ . '/../benchmark/Asset/utf-8-demo.txt');


for ($i = 0; $i < 6; $i++) {
    $test .= $test;
}
print (strlen($test).PHP_EOL);
$b->do($test);
print(PHP_EOL);
$b->showResults();
