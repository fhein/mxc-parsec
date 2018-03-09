<?php
include __DIR__ . '/../autoload.php';
use Mxc\Parsec\Encoding\CodePageGenerator;

$cg = CodePageGenerator::fromSourceDirectory('Microsoft/Windows');
$cg = CodePageGenerator::fromSourceDirectory('ISO8859');
print('Done.'.PHP_EOL);
