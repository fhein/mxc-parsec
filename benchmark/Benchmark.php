<?php

namespace Mxc\Benchmark\Parsec;

class Benchmark
{

    const CLASSES = [
        HoehrmannDecoder::class,
        Utf8Decoder::class
    ];

    const INDENT = 4;
    protected $indent = null;
    protected $results = [];
    protected $methods = [];

    public function __construct(array $classes = [])
    {
        if (empty($classes)) {
            $classes = self::CLASSES;
        }

        foreach ($classes as $class) {
            $this->registerMethods($class);
        }
        //var_dump($this->methods);
    }

    public function registerMethods(string $class)
    {
        if (! is_array($class::BENCHMARK)) {
            return;
        }
        foreach ($class::BENCHMARK as $method) {
            if (! is_string($method)) {
                continue;
            }
            if (! method_exists($class, $method)) {
                continue;
            }
            $this->methods[] = [
                'class'  => $class,
                'method' => $method,
            ];
        }
    }

    public function do(string $s)
    {
        $this->results = [];
        foreach ($this->methods as $method) {
            $class = new $method['class'];
            $action = is_callable([$class, $method['method']]) ? [$class, $method['method']] : null;
            if (! $action) {
                continue;
            }
            print('Running '. $method['method'] . PHP_EOL);
            $t = -microtime(true);
            $result = $action($s);
            $t += microtime(true);
            $class = substr($method['class'], strrpos($method['class'], '\\') + 1);
            $this->results[] =
            [
                'class' => $class,
                'method' => $method['method'],
                'sample_size' => strlen($s),
                'time' => $t
            ];
            assert($result === true, $method['method']);
        }
    }

    public function indent($level) : string
    {
        if (! $this->indent) {
            $this->indent = str_repeat(' ', self::INDENT);
        }
        $indent = '';
        for ($i = 0; $i < $level; $i++) {
            $indent .= $this->indent;
        }
        return $indent;
    }

    protected function println(string $s)
    {
        print($s . PHP_EOL);
    }

    public function showResults()
    {
        $curClass = '';
        $class = '';
        $method = '';
        $sample_size = 0;
        $time = 0;
        $max = 0;
        foreach ($this->results as $result) {
            $align = max($max, strlen($result['method']));
        }
        $align += 10;
        foreach ($this->results as $result) {
            extract($result);
            if ($class != $curClass) {
                $curClass = $class;
                $this->println($class);
            }
            $this->println($this->indent(1). str_pad($method, $align + 4) . ": " . $time);
        }
    }
}
