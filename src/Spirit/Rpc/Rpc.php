<?php

namespace Mxc\Parsec\Rpc;

use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Service\ParserBuilder;
use Mxc\Parsec\Qi\Char\SpaceParser;

/**
 * Rpc - class being exposed by RPC-server
 */
class Rpc
{
    /**
     * Parse input using parser and skipper
     *
     * @param  array $parser
     * @param  string $start
     * @param  string $input
     * @param  int $selectionEnd
     * @param  int $selectionStart
     * @param  array $skipper
     * @return array
     */
    public function parse($parser, $start, $input = null, $selectionStart = null, $selectionEnd = null, $skipper = null)
    {
        $result = $this->trace($parser, $start, $input, $selectionStart, $selectionEnd, $skipper);
        unset($result['actions']);
        return $result;
    }

    /**
     * Parse input using parser and skipper
     *
     * @param  array $parser
     * @param  string $start
     * @param  string $input
     * @param  int $selectionEnd
     * @param  int $selectionStart
     * @param  array $skipper
     * @return array
     */
    public function trace($parser, $start, $input = null, $selectionStart = null, $selectionEnd = null, $skipper = null)
    {
        $pm = new ParserManager();
        $pb = $pm->get(ParserBuilder::class);
        $pb->setDefinitions($parser);
        $rule = $pb->getRule($start);
        $this->setInput($input);
        //print($input);
        $rule->setSource($input, $selectionStart, $selectionEnd);

        $skipper = $skipper ?? $pm->build('space', ['42']);
        $result = $rule->parse($skipper);

        $result = [
            'result' => $result,
            'position' => $rule->getPos(),
            'actions' => $rule->getLog(),
            'attribute' => $rule->getAttribute()
        ];

        return $result;
    }

    /**
     * Set input
     *
     * @param  string $input
     * @return bool
     */
    public function setInput($input)
    {
        file_put_contents(__DIR__. '/../../../config/input.txt', $input);
        return true;
    }

    /**
     * Get input
     *
     * @return string
     */
    public function getInput()
    {
        return file_get_contents(__DIR__. '/../../../config/input.txt');
    }
}
