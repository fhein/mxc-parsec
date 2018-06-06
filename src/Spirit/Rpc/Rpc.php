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
     * @param  array $skipper
     * @param  string $input
     * @return bool
     */
    public function parse($parser, $input = null, $skipper = null)
    {
        $pm = new ParserManager();
        $pb = $pm->get(ParserBuilder::class);
        $pb->setDefinitions($parser);
        $rule = $pb->getRule('rule1');
        $rule->setSource($input);
        $this->setInput($input);

        $skipper = $skipper ?? $pm->build('space', [ '42']);
        $result = $rule->parse($skipper);

        $result = [
            'result' => $result,
            'position' => $rule->getPos(),
            'actions' => $rule->getLog()
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
